Features and usage
==================

This application is based on the Oro Platform and inherits it's base functionality.
See [Oro Platform documentation](https://www.orocrm.com/documentation/index/current/book) for more info.

The issue's, bugs reporting, functionality made on top of it consist of the features listed below.

## Issues list

List displaying all the issues.

####  The issue consist of:

- **code**: auto generated field, which uses counting algorithm with date as a prefix

- **summary**: a short title for the issue, detailed description is available on the issues's view page

- **type**: an issue may have one of three types - a story - which may have a subtasks, a bug, or a subtask
that can be only added to a story
	
- **priority**: issue may have different priority: trivial, major, critical, blocker

- **reporter**: issue author is marked as a reporter of the issue

- **assignee**: reporter assigns issue to choosen user

- **created at**: date when the issue was added

- **updated at**: every change in the issue's content updates this field

- **resolution**: when resolving the issue, user has to tell how the issue was resolved

- **tags**: issues may have assigned, dynamically added, tags


#### List may be filtered by every grid's column

Use the search form above the list to filter results

#### List may be sorted

Click on the header of column to sort the list, priority column is sortable
by it's defined priority level, not by it's name.

#### Options of the grid

Grid may be configured to display only selected columns. It also lets user
to change amount of displayed rows.

#### Row options

Every row representing issue in the table, has options to:
- View
- Edit
- Delete

Deletion may also be done in the bulk way.

#### Export

List of issues may be exported to the csv file using the "export" button at the header menu.

#### Importing

Issues may be imported from the CSV file. using the "import" button.
Template file may be downloaded from the context menu attached to that button.


## Issue view page

Issue view page besides of data that are also visible in the list view, is displaying:

* description
* collaborators - all users that made changes to the issue or was in charge of resolving it
* related issues - issue may have issues assigned as related
* subtasks - when issue is a story type, it may have child issues as subtasks
* parent issue - when issue is type of the subtasks, view page displays it's parent

#### Tags

User can view or add tags to the issue in the view page.

#### Notes

User can add notes to the issue and they will be visible on the view page.

#### Sending email

User can send e-mail with the current issue as the attachment.

#### Workflow

On the top of the page there is a workflow indicator that shows current step of resolving the issue.

Starting progress and changing steps may be done using the button in the header menu.

Some steps allow to transition backwards, some only forward.
Resolving the issue requires giving additional information about the way the issue was resoled.

Current step is the issue's status indicator.

## Adding a new issue

Adding new issue form can be accessed from various places, one of them is navigation menu at
the top of the screen.

User is able to choose from two types: bug or story. Subtask type is choosen automaticaly
when adding a subtask to story.

User can define summary, choose an assigne and fill desciption.

Moreover user can mark some issues as related to this one.

#### Adding subtasks

To add a subtasks to the story:
- enter the story's view page
- choose "add subtask" from menu in the header toolbar

## Search engine

To search issues and other system entries use the magnifier icon at the top left.

User can find issues by:
- summary
- code
- type
- priority
- status
- resolution
- reporter
- assignee

## Dashboard

Dashboard visible as the first screen of the application is configurable
and there are two additional widgets related to issue's system that can
be added.
    
To access dashboard from other pages, select the icon below the "Oro" logo.

To add a widget, use the "Add Widget" button in the header menu.

#### Issues chart widget

This widget is showing amount of issues in system, with the specified status.
Statuses are placed on the x axis. Every status is representing current issue's workflow
step.

#### Recently collaborated issues widget

This widget is displaying a small grid listing issues that user has been involved into
recently. To display more items, click on the "View all" link at the top of the widget.

## User view

Additional information and options related to issues can be found on the user's view page.

To access user's view page, click on the user name found for example on the issue's view page.

#### Grid of collaborated issues

Additional information showing grid of the issues that user was collaborating in
is displayed at the bottom section of the screen.

#### Adding issue

In the header at the user's view page there is a button "Add Issue" that opens
a popup window with a form and lets instantly add an issue and assign it the viewed user.

## Reports

Report of issues can be viewed and downloaded accessing the Reports menu
at the top of the page and choosing Issues. Download option is placed near the "All issues" title,
under the "options" drop down menu. The view is the similar view to the list of all issues.


