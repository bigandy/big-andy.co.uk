=== Gutenberg ===
Contributors: matveb, joen
Requires at least: 4.8
Tested up to: 4.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Block editor for WordPress. This is the development plugin for the new block editor in core. Warning: This is beta software, do not run on real sites!

== Description ==

The goal of the block editor is to make adding rich content to WordPress simple and enjoyable.

<strong>Warning: This is beta software, do not run on production sites!</strong>

The new post and page building experience will make writing rich posts effortless, making it easy to do what today might take shortcodes, custom HTML, or "mystery meat" embed discovery.

WordPress already supports a large amount of "blocks", but doesn't surface them very well, nor does it give them much in the way of layout options. By embracing the blocky nature of rich post content, we will surface the blocks that already exist, as well as provide more advanced layout options for each of them. This will allow you to easily compose beautiful posts like <a href="http://moc.co/sandbox/example-post/">this example</a>.

Gutenberg is built by many contributors and volunteers. You can see the full list of contributors in <a href="https://github.com/WordPress/gutenberg/blob/master/CONTRIBUTORS.md">the GitHub CONTRIBUTORS.md file</a> which we are continuously updating. You can follow along on <a href="https://github.com/WordPress/gutenberg">github.com/WordPress/gutenberg</a> and on the <a href="https://make.wordpress.org/core/tag/editor/">#editor tag on the make.wordpress.org blog</a>.

== Frequently Asked Questions ==

= How can I send feedback or get help with a bug? =

We'd love to hear your bug reports, feature suggestions and any other feedback! Please head over to <a href="https://github.com/WordPress/gutenberg/issues">the GitHub issues page</a> to search for existing issues or open a new one. While we'll try to triage issues reported here on the plugin forum, you'll get a faster response (and reduce duplication of effort) by keeping everything centralized in the GitHub repository.

= How can I contribute? =

The more the merrier! To get started, check out our <a href="https://github.com/WordPress/gutenberg/blob/master/CONTRIBUTING.md">guide for contributors</a>.

== Changelog ==

= 0.4.0 =
* Initial FAQ (in progress).
* API for handling pasted content. (Aim is to have specific handling for converting Word, Markdown, Google Docs to native WordPress blocks.)
* Added support for linking to a url on image blocks.
* Navigation between blocks using arrow keys.
* Added alternate Table block with TinyMCE functionality for adding/removing rows/cells, etc. Retired previous one.
* Parse more/noteaser comment tokens from core.
* Re-engineer the approach for rendering embed frames.
* First pass at adding aria-labels to blocks list.
* Setting up Jest for better testing environment.
* Improve performance of server-side parsing.
* Update blocks documentation with latest API functions and clearer examples.
* Use fixed position for notices.
* Make inline mode the default for Editable.
* Add actions for plugins to register frontend and editor assets.
* Supress gallery settings sidebar on media library when editing gallery.
* Validate save and edit render when registering a block.
* Prevent media library modal from opening when loading placeholders.
* Update to sidebar design and behaviour on mobile.
* Improve font-size in inserter and latest posts block.
* Improve rendering of button block in the front end.
* Add aria-label to edit image button.
* Add aria-label to embed input url input.
* Use pointer cursor for tabs in inserter.
* Update design docs with regard to selected/unselected states.
* Improve generation of wp-block-* classes for consistency.
* Select first cell of table block when initializing.
* Fix wide and full alignment on the front-end when images have no caption.
* Fix initial state of freeform block.
* Fix ability to navigate to resource on link viewer.
* Fix clearing floats on inserter.
* Fix loading of images in library.
* Fix auto-focusing on table block being too agressive.
* Clean double reference to pegjs in dependencies.
* Include messages to ease debugging parser.
* Check for exact match for serialized content in parser tests.
* Add allow-presentation to fix issue with sandboxed iframe in Chrome.
* Declare use of classnames module consistently.
* Add translation to embed title.
* Add missing text domains and adjust PHPCS to warn about them.
* Added template for creating new issues including mentions of version number.

= 0.3.0 =
* Added framework for notices and implemented publishing and saving ones.
* Implemented tabs on the inserter.
* Added text and image quick inserts next to inserter icon at the end of the post.
* Generate front-end styles for core blocks and enqueue them.
* Include generated block classname in edit environment.
* Added "edit image" button to image and cover image blocks.
* Added option to visually crop images in galleries for nicer alignment.
* Added option to disable dimming the background in cover images.
* Added buffer for multi-select flows.
* Added option to display date and to configure number of posts in LatestPosts block.
* Added PHP parser based on PEG.js to unify grammars.
* Split block styles for display so they can be loaded on the theme.
* Auto-focusing for inserter search field.
* Added text formatting to CoverImage block.
* Added toggle option for fixed background in CoverImage.
* Switched to store attributes in unescaped JSON format within the comments.
* Added placeholder for all text blocks.
* Added placeholder text for headings, quotes, etc.
* Added BlockDescription component and applied it to several blocks.
* Implemented sandboxing iframe for embeds.
* Include alignment classes on embeds with wrappers.
* Changed the block name declaration for embeds to be "core-embed/name-of-embed".
* Simplified and made more robust the rendering of embeds.
* Different fixes for quote blocks (parsing and transformations).
* Improve display of text within cover image.
* Fixed placeholder positioning in several blocks.
* Fixed parsing of HTML block.
* Fixed toolbar calculations on blocks without toolbars.
* Added heading alignments and levels to inspector.
* Added sticky post setting and toggle.
* Added focus styles to inserter search.
* Add design blueprints and principles to the storybook.
* Enhance FormTokenField with accessibility improvements.
* Load word-count module.
* Updated icons for trash button, and Custom HTML.
* Design tweaks for inserter, placeholders, and responsiveness.
* Improvements to sidebar headings and gallery margins.
* Allow deleting selected blocks with "delete" key.
* Return more than 10 categories/tags in post settings.
* Accessibility improvements with FormToggle.
* Fix media button in gallery placeholder.
* Fix sidebar breadcrumb.
* Fix for block-mover when blocks are floated.
* Fixed inserting Freeform block (now classic text).
* Fixed missing keys on inserter.
* Updated drop-cap class implementation.
* Showcasing full-width cover image in demo content.
* Copy fixes on demo content.
* Hide meta-boxes icons for screen readers.
* Handle null values in link attributes.

= 0.2.0 =
* Include "paste" as default plugin in Editable.
* Extract block alignment controls as a reusable component.
* Added button to delete a block.
* Added button to open block settings in the inspector.
* New block: Custom HTML (to write your own HTML and preview it).
* New block: Cover Image (with text over image support).
* Rename "Freeform" block to "Classic Text".
* Added support for pages and custom post types.
* Improve display of "saving" label while saving.
* Drop usage of controls property in favor of components in render.
* Add ability to select all blocks with ctrl/command+A.
* Automatically generate wrapper class for styling blocks.
* Avoid triggering multi-select on right click.
* Improve target of post previewing.
* Use imports instead of accessing the wp global.
* Add block alignment and proper placeholders to pullquote block.
* Wait for wp.api before loading the editor. (Interim solution.)
* Adding several reusable inspector controls.
* Design improvements to floats, switcher, and headings.
* Add width classes on figure wrapper when using captions in images.
* Add image alt attributes.
* Added html generation for photo type embeds.
* Make sure plugin is run on WP 4.8.
* Update revisions button to only show when there are revisions.
* Parsing fixes on do_blocks.
* Avoid being keyboard trapped on editor content.
* Don't show block toolbars when pressing modifier keys.
* Fix overlapping controls in Button block.
* Fix post-title line height.
* Fix parsing void blocks.
* Fix splitting inline Editable instances with shift+enter.
* Fix transformation between text and list, and quote and list.
* Fix saving new posts by making post-type mandatory.
* Render popovers above all elements.
* Improvements to block deletion using backspace.
* Changing the way block outlines are rendered on hover.
* Updated PHP parser to handle shorthand block syntax, and fix newlines.
* Ability to cancel adding a link from link menu.

= 0.1.0 =
* First release of the plugin.
