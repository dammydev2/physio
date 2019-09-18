 session handlers
   which do the real work of participating in the internal PHP session workflow.
 * [BC BREAK] Introduced mock implementations of `SessionStorage` to enable unit
   and functional testing without starting real PHP sessions.  Removed
   `ArraySessionStorage`, and replaced with `MockArraySessionStorage` for unit
   tests; removed `FilesystemSessionStorage`, and replaced with`MockFileSessionStorage`
   for functional tests.  These do not interact with global session ini
   configuration values, session functions or `$_SESSION` superglobal. This means
   they can be configured directly allowing multiple instances to work without
   conflicting in the same PHP process.
 * [BC BREAK] Removed the `close()` method from the `Session` class, as this is
   now redundant.
 * Deprecated the following methods from the Session class: `setFlash()`, `setFlashes()`
   `getFlash()`, `hasFlash()`, and `removeFlash()`. Use `getFlashBag()` instead
   which returns a `FlashBagInterface`.
 * `Session->clear()` now only clears session attributes as before it cleared
   flash messages and attributes. `Session->getFlashBag()->all()` clears flashes now.
 * Session data is now managed by `SessionBagInterface` to better encapsulate
   session data.
 * Refactored session attribute and flash messages system to their own
  `SessionBagInterface` implementations.
 * Added `FlashBag`. Flashes expire when retrieved by `get()` or `all()`. This
   implementation is ESI compatible.
 * Added `AutoExpireFlashBag` (default) to replicate Symfony 2.0.x auto expire
   behavior of messages auto expiring after one page page load.  Messages must
   be retrieved by `get()` or `all()`.
 * Added `Symfony\Component\HttpFoundation\Attribute\AttributeBag` to replicate
   attributes storage behavior from 2.0.x (default).
 * Added `Symfony\Component\HttpFoundation\Attribute\NamespacedAttributeBag` for
   namespace session attributes.
 * Flash API can stores messages in an array so there may be multiple messages
   per flash type.  The old `Session` class