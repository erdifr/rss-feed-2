# RSS Feed 2
A plugin that adds an [RSS web syndication](http://www.rssboard.org/rss-specification) feed to [Bludit - Flat-File CMS](https://github.com/bludit/bludit) powered websites.


## What does it do?
RSS Feed 2 has all the functionality of [Bludit RSS Feed plugin](https://github.com/bludit/bludit/tree/master/bl-plugins/rss) plus a little extra that I wanted.


### What was added

1. ```copyright``` element added to RSS ```channel``` element.

	- Default ```copyright``` is ```CC By-SA 4.0```.
	- Configured from plugin settings page.
	- Leave blank to disable use.

2. ```language``` element added to RSS ```channel``` element.

	- Uses ```Theme::lang()```.
	- Not configurable. It is automatic.

3. ```generator``` element added to RSS ```channel``` element.

	- Default ```generator``` is ```Bludit - Flat-File CMS```.
	- Configured from plugin settings page.
	- Leave blank to disable use.

4. ```ttl``` element added to RSS ```channel``` element.

	- Default ```ttl``` is ```60```.
	- Configured from plugin settings page.
	- Leave blank to disable use.

5. ```category``` element added to RSS ```item``` element.

	- ```category``` is only added if the page has an assigned category.
	- Not configurable. It is automatic.


### What was changed

1. RSS Feed URL changed from ```http://example.com/rss.xml``` to ```http://example.com/rss2.xml```.

	- This was done to not interfere with the [Bludit RSS Feed plugin](https://github.com/bludit/bludit/tree/master/bl-plugins/rss).

2. RSS Feed link title changed from ```RSS Feed``` to ```Site title - RSS Feed```.

	- Helps some feed readers to select correct feed title


## Requirements
- [Bludit](https://github.com/bludit/bludit) v3.8.1 - Not tested with any other version.


## Credit
All inspiration for this plugin came from the [Bludit RSS Feed plugin](https://github.com/bludit/bludit/tree/master/bl-plugins/rss).


## License
RSS Feed 2 is open source software licensed under the [MIT license](https://opensource.org/licenses/MIT).