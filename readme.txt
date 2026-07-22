=== WP Zoomy Integration for Zoom and Uncanny Automator ===
Contributors: wpzoomy
Tags: zoom, automator, uncanny automator, meetings
Requires at least: 5.8
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Connects WP Zoomy with Uncanny Automator for Zoom meeting automation.

== Description ==

WP Zoomy Integration for Zoom and Uncanny Automator adds Zoom triggers and actions to Uncanny Automator when used with **WP Zoomy** (wp-zoom-addon) and **Uncanny Automator**.

**Triggers**

* A Zoom meeting is created
* A Zoom meeting is updated

**Actions**

* Create a Zoom meeting
* Register an attendee for a Zoom meeting

== Installation ==

1. Install and activate [Uncanny Automator](https://wordpress.org/plugins/uncanny-automator/) and **WP Zoomy** (wp-zoom-addon).
2. Upload the plugin folder to `/wp-content/plugins/wpzoomy-integration-zoom-uncanny-automator/`.
3. Activate the plugin through the Plugins screen.
4. Create or edit an Automator recipe and select Zoom triggers or actions.

== Frequently Asked Questions ==

= What plugins are required? =

Uncanny Automator and WP Zoomy (wp-zoom-addon) must both be installed and active.

= Why is my Create Meeting action failing? =

Assign a Zoom host ID to at least one WordPress user under Zoom Meetings settings, and ensure Zoom API credentials are configured in WP Zoomy.

= Why is Register Attendee failing? =

The meeting must have Zoom registration enabled. Meetings created by this integration use automatic approval registration so attendees can be registered. Existing meetings need registration enabled in Zoom/WP Zoomy. Zoom Pro or higher is required for meeting registration.

== External services ==

This plugin connects to the Zoom API (api.zoom.us) so Uncanny Automator recipes can create Zoom meetings and register attendees. Those features only work when WP Zoomy is installed and configured with your Zoom account credentials.

When a recipe runs the Create Meeting action, meeting details from the recipe (such as topic, start time, duration, and host) are sent to Zoom to create the meeting.

When a recipe runs the Register Attendee action, the attendee email, first name, and last name from the recipe are sent to Zoom to register that person for the selected meeting.

Data is sent only when those Automator actions run. No data is sent on every page load.

This service is provided by Zoom Video Communications, Inc.:
[Terms of Service](https://www.zoom.com/en/trust/terms/)
[Privacy Statement](https://www.zoom.com/en/trust/privacy/privacy-statement/)

== Changelog ==

= 1.2.4 =
* Hardened Zoom API requests and input handling for WordPress.org security review

= 1.2.3 =
* Fixed a problem where the “Create meeting” and “Register attendee” settings would not open properly when building a recipe

= 1.2.2 =
* Fixed Zoom actions not expanding in the recipe builder (sentence tokens now match action_meta / options_group)

= 1.2.1 =
* Fixed trigger tokens for current Automator API (define_tokens)
* Create Meeting now enables auto-approve registration so Register Attendee works
* Added action tokens for created meetings and registrant join URLs
* Aligned Meeting Updated trigger tokens with Meeting Created
* Corrected Plugin URI

= 1.2.0 =
* Removed webinar triggers (requires separate Zoom webinar license; planned for a future release)

= 1.1.0 =
* Added Zoom triggers with dynamic tokens
* Added Create Meeting and Register Attendee actions
* Added dependency checks and Automator library submission polish

= 1.0.0 =
* Initial meeting created trigger


