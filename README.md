# TK Labels
**Warning** This is a Prototype!
Special thanks to [Brandon Weigel][bweigel] for reporting a [critical issue](https://github.com/MorganDawe/tk_labels/issues/7) during IslandoraCon 2022.

This module provides a block that will display [TK Labels][tklabels].
![Block](/images/block.png)

It has been inspired by [islandora_tk_labels][i7tklabels]. Written by [Brandon Weigel][bweigel].

## Configuration

This module provides a block that can be configured at `admin/structure/block/manage/tklabelsblock`.

![Configuration](/images/config.png)

**Note** This is a protoype: The API base URL is configurable to allow switching between the test and production APIs. It would be best to make this a select.

For the block to display the content type needs to contain a field with the machine name `tk_project_id` that maps to the project badges to display.

![Field Creation](/images/field.png)

**Note** This is a protoype: A better method would be a custom field type.

## Installation

Install as usual, see [this][install] for further information.

## Troubleshooting/Issues

Having problems or solved a problem? Contact
[discoverygarden](http://support.discoverygarden.ca).

## Maintainers/Sponsors

Current maintainers:

* [discoverygarden](http://www.discoverygarden.ca)

## Development

If you would like to contribute to this module create an issue, pull request
and or contact
[discoverygarden](http://support.discoverygarden.ca).
There is a [list of projects](https://anth-ja77-lc-dev-42d5.uc.r.appspot.com/api/v1/projects/) that could be used for development.

## License

[GPLv2][gplv2]

[gplv2]: http://www.gnu.org/licenses/gpl-2.0.txt
[install]: https://www.drupal.org/docs/extending-drupal/installing-modules
[tklabels]: https://localcontexts.org/
[i7tklabels]: https://github.com/bondjimbond/islandora_tk_labels
[bweigel]: https://github.com/bondjimbond
