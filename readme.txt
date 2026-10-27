=== Zoomy Automator Integration ===
Contributors: wpzoomy
Tags: zoom, automator, uncanny automator, meetings
Requires at least: 5.8
Tested up to: 7.1.1
Requires PHP: 7.5
Stable tag: 1.2.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Zoomy Automator Integration connects WP Zoomy with Uncanny Automator for Zoom meeting automation.

== Description ==

**Dedicated Support**
For quick support and assistance, please contact us [here](https://elearningevolve.com/contact/).

Zoomy Automator Integration adds Zoom triggers and actions to Uncanny Automator when used with **WP Zoomy** (wp-zoom-addon) and **Uncanny Automator**.

**Triggers**

* A Zoom meeting is created
* A Zoom meeting is updated

**Actions**

* Create a Zoom meeting
* Register an attendee for a Zoom meeting

== Installation ==

1. Install and activate [Uncanny Automator](https://wordpress.org/plugins/uncanny-automator/) and **WP Zoomy** (wp-zoom-addon).
2. Upload the plugin folder to `/wp-content/plugins/zoomy-automator-integration/`.
3. Activate the plugin through the Plugins screen.
4. Create or edit an Automator recipe and select Zoom triggers or actions.

== Frequently Asked Questions ==

= What plugins do I need? =

Uncanny Automator and WP Zoomy (wp-zoom-addon) must both be installed and active. Without them, Zoom triggers and actions will not load.

= I installed the plugin but I don’t see Zoom in Automator. Why? =

Check that Uncanny Automator and WP Zoomy are both active. If either is missing, an admin notice appears and the WP Zoomy integration will not load.

= How do I connect my Zoom account? =

Set up your Zoom API credentials in WP Zoomy. This plugin uses that connection and does not add a separate Zoom login.

= Where do I find the Zoom triggers and actions? =

In Automator, open a recipe and look under WP Zoomy.

= What does this plugin do? =

Triggers:

* A Zoom meeting is created
* A Zoom meeting is updated

Actions:

* Create a Zoom meeting
* Register an attendee for a Zoom meeting

= When does a recipe actually start? =

When a meeting is created or updated through WP Zoomy on your site. Changes made only inside the Zoom website do not start these recipes.

= Creating Meetings =

Questions about creating Zoom meetings from an Automator recipe.

= Why isn’t my meeting being created? =

Common causes:

* Zoom API credentials are not configured in WP Zoomy
* No WordPress user has a Zoom host ID assigned in Zoom Meetings settings
* Meeting topic, start time, or duration is missing

= What start time format should I use? =

Use `YYYY-MM-DD HH:MM:SS` in your WordPress site timezone. Example: `2026-07-25 15:00:00`.

= Why isn’t attendee registration working? =

Check all of the following:

* The meeting has Zoom registration enabled
* Meetings created by this plugin use automatic-approval registration
* Existing meetings need registration enabled manually in Zoom/WP Zoomy
* Zoom Pro or higher is required for meeting registration
* A valid email and first name are required

= Can I create a meeting and register someone in the same recipe? =

Yes. Pass the Meeting ID token from Create a Zoom meeting into Register an attendee.

= Can I reuse the meeting link or ID in later steps? =

Yes. Use tokens such as Meeting ID, Topic, Join URL, Start Time, Host ID, and (after registration) Registrant Join URL.

= Do I need a paid Zoom plan? =

Creating meetings works with a normal Zoom account connected in WP Zoomy. Registering attendees generally requires Zoom Pro or higher because meeting registration is a paid Zoom feature.

= Does this work with Zoom webinars? =

No. Webinar options were removed. This version supports meetings only.

= Where can I get support? =

For quick support and assistance, contact us here: https://elearningevolve.com/contact/

== External services ==

This plugin connects to the Zoom API (api.zoom.us) so Uncanny Automator recipes can create Zoom meetings and register attendees. Those features only work when WP Zoomy is installed and configured with your Zoom account credentials.

When a recipe runs the Create Meeting action, meeting details from the recipe (such as topic, start time, duration, and host) are sent to Zoom to create the meeting.

When a recipe runs the Register Attendee action, the attendee email, first name, and last name from the recipe are sent to Zoom to register that person for the selected meeting.

Data is sent only when those Automator actions run. No data is sent on every page load.

This service is provided by Zoom Video Communications, Inc.:
[Terms of Service](https://www.zoom.com/en/trust/terms/)
[Privacy Statement](https://www.zoom.com/en/trust/privacy/privacy-statement/)

== Our Other Plugins ==

1. [Add From Server Reloaded](https://wordpress.org/plugins/add-from-server-reloaded/)
2. [Evolve's Learndash To Lifter](https://wordpress.org/plugins/evolve-learndash-to-lifter/)
3. [MasterQuiz AI](https://masterquiz.io/)
4. [Simple Email Scheduler](https://wordpress.org/plugins/simple-email-scheduler/)
5. [TopBar Buddy](https://wordpress.org/plugins/topbar-buddy/)
6. [UpdateGuard – Safe Update Manager](https://wordpress.org/plugins/updateguard/)
7. [Virtual Classroom – Video Conferencing & Online Meeting with BigBlueButton(Free)](https://wordpress.org/plugins/video-conferencing-with-bbb/)
8. [Virtual Classroom for WordPress (Pro)](https://elearningevolve.com/products/wp-virtual-classroom/)

== Changelog ==

= 1.2.6 =
* Renamed plugin to Zoomy Automator Integration (dropped WP from name/slug)

= 1.2.5 =
* Renamed plugin to WP Zoomy Automator Integration

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


