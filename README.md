# RSS Feed 2
A plugin that adds an [RSS web syndication](http://www.rssboard.org/rss-specification) feed to [Bludit - Flat-File CMS](https://github.com/bludit/bludit) powered websites.



## What does it do?
RSS Feed 2 has all the functionality of [Bludit RSS Feed plugin](https://github.com/bludit/bludit/tree/master/bl-plugins/rss) plus a little extra that I wanted.



### What was added

1. ```copyright``` element added to RSS ```channel``` element.

	- No default ```copyright``` set.
	- Configured from plugin settings page.
	- Select ```DISABLE``` to disable use.

2. ```language``` element added to RSS ```channel``` element.

	- Uses ```Theme::lang()```.
	- Not configurable. It is automatic.

3. ```generator``` element added to RSS ```channel``` element.

	- Default ```generator``` is ```Bludit - Flat-File CMS```.
	- Configured from plugin settings page.
	- Valid input: ```!```, ```-```, ```_```, ```.```, ```a-z```, ```A-Z```, ```À-ž```, ```0-9```.
	- Leave blank to disable use.

4. ```ttl``` element added to RSS ```channel``` element.

	- Default ```ttl``` is ```60```.
	- Configured from plugin settings page.
	- Valid input range ```0``` - ```1440```.
	- Select ```0``` to disable use.

5. ```category``` element added to RSS ```item``` element.

	- ```category``` is only added if the page has an assigned category.
	- Not configurable. It is automatic.




## Requirements
- [Bludit](https://github.com/bludit/bludit) v3.8.1 - Not tested with any other version.



## Version History

- ```v0.0.3```

	1. Fixed a typo in ```plugin.php``` and bumped ```rss-feed-2``` to ```v0.0.3```.

- ```v0.0.2```

	1. RSS feed URL changed from ```http://example.com/rss2.xml``` to ```http://example.com/feed.rss```.

	2. RSS feed item limit settings menu now uses a ```number``` input.

	3. RSS feed copyright settings menu now uses a ```select``` input.

	4. RSS feed ttl settings menu now uses a ```number``` input.

	5. RSS feed generator settings menu input now accepts ```À-ž``` characters.

	6. More information added to settings menu sections - on-screen and mouseover.


- ```v0.0.1``` - Initial version.

	1. RSS Feed URL changed from ```http://example.com/rss.xml``` to ```http://example.com/rss2.xml```.

		- This was done to not interfere with the [Bludit RSS Feed plugin](https://github.com/bludit/bludit/tree/master/bl-plugins/rss).

	2. RSS Feed link title changed from ```RSS Feed``` to ```Site title - RSS Feed```.

		- Helps some feed readers to select correct feed title



## Credit
All inspiration for this plugin came from the [Bludit RSS Feed plugin](https://github.com/bludit/bludit/tree/master/bl-plugins/rss).



## License
RSS Feed 2 is open source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
