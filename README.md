# FOD Attest 281.86

## Background information

Starting from 2023 onward the tax certificate 281.86 (Costs of childcare) must be delivered to the government.
This will be done through Belcotax On Web

[All technical documentation](https://financien.belgium.be/nl/E-services/Belcotaxonweb/technische-documentatie) can be found here.
Every following link is also found on the website

Every year specs get updated and this package must be updated accordingly.
This is the [brochure](https://financien.belgium.be/sites/default/files/downloads/161-belcotax-brochure-2022-20221209-nl.pdf) with all changes and explanation of fields/validation of fields.
The change in specs also means there is a new validation module. Sadly the government only provides us with the new validation module at the start of a new year.

The module for Fiscal Year 2021 can be found [here](https://ccff02.minfin.fgov.be/CCFF_SP7_2021/jnlp/belcotax.jnlp). This means this module still validates on addresses being only 32 characters long while the spec changed to 200 chars for this fiscal year.

There also is an [FAQ](https://financien.belgium.be/sites/default/files/downloads/161-belcotax-brochure-2022-20221209-nl.pdf) specifically answering questions regarding certificate 281.86.

## Technical information

This package is written in **PHP7.4** because it will also be used by applications that lag behind on PHP versions.
In an ideal world we write this packaged in the latest PHP version and have rector make a build to an older PHP version.
This package would be the ideal

## Code Documentation

Code should always be documented as to what they mean in function of the tax certificate

## Dependencies

When this package requires a new dependency make sure to install it through the docker container.
That way we can make sure the dependency is never out of sync with the php/composer version

## Credits

- [Jan Dries][link-author]
- [Gert van den Buijs][link-coauthor]
- [All Contributors][link-contributors]

Development of this library is sponsored by [Tactics]([link-owner]).

## License

The Lesser GPL version 3 or later. Please see [License File](LICENSE.md) for more information.

[link-satis]: https://satis.tactics.be/#tactics/fod-attest-28186
[link-author]: https://github.com/TacticsJan
[link-coauthor]: https://github.com/gertvdb
[link-owner]: https://github.com/Tactics

[link-contributors]: ../../contributors
