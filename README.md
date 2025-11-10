# CiviCRM: FormBuilder hash filters

This CiviCRM extension allows site administrators to specify that, for certain FormBuilder forms, certain url parameters should be hashed in a way that makes them hard to guess.

**Example use case**
- You have a FormBuilder form which includes a SearchKit display, for the purpose of allowing anonymous users to view a specific set of data at a given URL, e.g. https://example.org/civicrm/member-status/#?member_id=123 displays basic current membership status information for a member with `membership_id=123`
- An anonymous user who knows this URL _should_ be able to view the information for member `123`, but you don't want them to start mining your data by changing the `membership_id` query parameter (e.g. `/#?member_id=124`, `/#?member_id=125`, etc.)
- By using this extension, you can generate (and share) a URL in which the membership_id is hashed in an unguessable way (e.g. https://example.org/civicrm/member-status/#?member_id=5a53990cc6f92f7d5cae2e4c8930cc8eb61694efa029c9d8eab9b3536d72cf9|123), so that merely changing the `123` to `124` will create a non-functional URL, thus protecting the privacy of member '124' (and other members).

## Configuration
In lieu of a configuration UI, this extension uses settings defined in civicrm.settings.php. (A configuration UI would be nice to have, but does not yet exist.)

Example (to be added to civicrm.settings.php):
```php
global $civicrm_setting;
$civicrm_setting['com.joineryhq.fbhash']['com.joineryhq.fbhash'] = [
  'algorithm' => 'sha3-512',
  'fbhash_hashedFilters' => [
    'afsearchMemberStatus' => [
      'member_id',
    ],
    'afsearchContactInfo' => [
      'contact_id',
    ],
  ],
];
```

This nested array format is fragile but explicit, allowing to specify any url parameters for any FormBuilder form. The format is as follows:
```php
global $civicrm_setting;
$civicrm_setting['com.joineryhq.fbhash']['com.joineryhq.fbhash'] = [
  'algorithm' => [hashAlgo], 
  'fbhash_hashedFilters'] = [
    [afformName] => [
      [queryParameterName],
    ],
  ],
];
```

### [hashAlgo]
Any available hash algorithm supported by PHP. See output of PHP's `hash_algos()`
for a valid list.

If no valid algorithm is specified, the extension will use 'sha-256'.

### [afformName]
Machine name for the given FormBuilder form.

### [queryParameterName]
Query paramter name, as defined in FormBuilder display settings Filters (url).


## Usage

The hashing functionality is unlikely to be useful on its own. This extension
provides a v4 API, `Fbhash.HashAfformUrl`, which can be called like so:

```php
    $filters = ['member_id' => '123'];
    $afformName = 'afsearchMembershipStatus';
    $fbhash = \Civi\Api4\Fbhash::hashAfformUrl()
      ->setCheckPermissions(FALSE)
      ->setFilters($filters)
      ->setAfformName($afformName)
      ->execute()
      ->first();
    $hashedUrl = $fbhash['url'];
```

- Only query parameters which are defined in `$civicrm_setting['com.joineryhq.fbhash']['hashedFilters']` will be hashed.
- For any FormBuilder form so defined, the defined query parameters will be hashed, and any access to that form will require that the given parameters have a valid hash.
  - An invalid query parameter hash will cause no records to be loaded when the FormBuilder form is accessed.
  - An empty value constitutes an invalid hash, thus making the given query parameter _required_.

## Ideas for improvement
This project is open to PRs for improvements, including the following:
- Creation of a UI to replace configuration in civicrm.settings.php.
- Display of a "No records found" or "Permission denied" message in the case of an invalid hash value, instead of the current behavior showing merely blank results.


## Installation
* Copy this package to your CiviCRM extensions directory (in WordPress, that's typically `[document-root]/wp-content/uploads/civicrm/ext`
* In CiviCRM, enable the extension "AJAX API Identifier".

## Support

Support for this plugin is handled under Joinery's ["As-Is Support" policy](https://joineryhq.com/software-support-levels#as-is-support).

Public issue queue for this plugin: https://github.com/JoineryHQ/com.joineryhq.fbhash/issues