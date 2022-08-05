# TK Labels 
**Warning** This is a Prototype! There is a [critical issue](https://github.com/MorganDawe/tk_labels/issues/7) that needs to be addressed before any adoption.

This module provides a block that will display [TK Labels][tklabels].
![Block](/images/block.png)

It has been inspired by [islandora_tk_labels][i7tklabels]. Written by [Brandon Weigel][bweigel].

## Configuration

This module provides a block that can be configured at `admin/structure/block/manage/tklabelsblock`.

![Configuration](/images/config.png)

**Note** This is a protoype: The API base URL is configurable to allow switching between the test and production APIs. It would be best to make this a select.

For the block to display the contenty type needs to contain a field with the machine name `field_notice_type` that maps to the badge to display. 

![Field Creation](/images/field.png)

**Note** This is a protoype: A better method would be a custom multivalued field type coupled with a taxonomy migrated during module installation.

## Installation

Install as usual, see [this][install] for further information.

## Maintainers/Sponsors

Current maintainers:

* Morgan Dawe
* William Panting

## Development

If you would like to contribute to this module create an issue or pull request.

## License

[GPLv2][gplv2]

[gplv2]: http://www.gnu.org/licenses/gpl-2.0.txt
[install]: https://www.drupal.org/docs/extending-drupal/installing-modules
[tklabels]: https://localcontexts.org/
[i7tklabels]: https://github.com/bondjimbond/islandora_tk_labels
[bweigel]: https://github.com/bondjimbond
