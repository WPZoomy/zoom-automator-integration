# Zoomy Automator Integration

**Contributors:** wpzoomy  
**Tags:** zoom, automator, uncanny automator, meetings  
**Requires at least:** 5.8  
**Tested up to:** 7.0  
**Requires PHP:** 7.4  
**Stable tag:** 1.2.6  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Zoomy Automator Integration connects WP Zoomy with Uncanny Automator for Zoom meeting automation.

## Description

Zoomy Automator Integration adds Zoom triggers and actions to Uncanny Automator when used with [WP Zoomy](https://wpzoomy.com/) (wp-zoom-addon) and [Uncanny Automator](https://wordpress.org/plugins/uncanny-automator/).

**Triggers**

* A Zoom meeting is created
* A Zoom meeting is updated

**Actions**

* Create a Zoom meeting
* Register an attendee for a Zoom meeting

## Installation

1. Install and activate [Uncanny Automator](https://wordpress.org/plugins/uncanny-automator/) and [WP Zoomy](https://wpzoomy.com/) (wp-zoom-addon).
2. Upload the plugin folder to `/wp-content/plugins/zoomy-automator-integration/`.
3. Activate the plugin through the Plugins screen.
4. Create or edit an Automator recipe and select Zoom triggers or actions.

## Frequently Asked Questions

### What plugins are required?

[Uncanny Automator](https://wordpress.org/plugins/uncanny-automator/) and [WP Zoomy](https://wpzoomy.com/) (wp-zoom-addon) must both be installed and active.

### Why is my Create Meeting action failing?

Assign a Zoom host ID to at least one WordPress user under Zoom Meetings settings, and ensure Zoom API credentials are configured in WP Zoomy.

### Why is Register Attendee failing?

The meeting must have Zoom registration enabled. Meetings created by this integration use automatic approval registration so attendees can be registered. Existing meetings need registration enabled in Zoom/WP Zoomy. Zoom Pro or higher is required for meeting registration.

## External services

This plugin connects to the Zoom API (api.zoom.us) so Uncanny Automator recipes can create Zoom meetings and register attendees. Those features only work when WP Zoomy is installed and configured with your Zoom account credentials.

When a recipe runs the Create Meeting action, meeting details from the recipe (such as topic, start time, duration, and host) are sent to Zoom to create the meeting.

When a recipe runs the Register Attendee action, the attendee email, first name, and last name from the recipe are sent to Zoom to register that person for the selected meeting.

Data is sent only when those Automator actions run. No data is sent on every page load.

This service is provided by Zoom Video Communications, Inc.:
[Terms of Service](https://www.zoom.com/en/trust/terms/)
[Privacy Statement](https://www.zoom.com/en/trust/privacy/privacy-statement/)

## Changelog

### 1.2.6

* Renamed plugin to Zoomy Automator Integration (dropped WP from name/slug)

### 1.2.5

* Renamed plugin to WP Zoomy Automator Integration

### 1.2.4

* Hardened Zoom API requests and input handling for WordPress.org security review

### 1.2.3

* Fixed a problem where the “Create meeting” and “Register attendee” settings would not open properly when building a recipe

### 1.2.2

* Fixed Zoom actions so you can click them and fill in the details in the recipe builder

### 1.2.1

* Meeting details (like ID, topic, and join link) now show correctly after a recipe runs
* New meetings created by Automator now allow people to register automatically
* After creating a meeting or registering someone, you can reuse their meeting/join info in later steps
* “Meeting updated” now shares the same useful meeting details as “Meeting created”
* Updated the plugin’s website link

### 1.2.0

* Removed webinar options for now (those need a separate Zoom webinar plan; may come back later)

### 1.1.0

* Added ways to start a recipe when a Zoom meeting is created or updated
* Added ways to create a Zoom meeting and register an attendee from a recipe
* Added clearer messages if required plugins are missing, and polished the listing for Automator

### 1.0.0

* First release: start a recipe when a Zoom meeting is created
