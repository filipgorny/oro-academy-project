## List of known issues

#### Discussed already

* When browsing with navigation menu, the page title is increasingly adding "Add - " prefix, and title
won't change using twig "pageTitle" variable, it shows "Dashboard" or goes blank but with that prefix.

* A colon is displaying before Issue's reporter name, at the header of the view page. I am not able to locate
where it comes from.

* Adding "Issues by status" dashboard widget, causes the spinner loader to keep being visible and the
addition doesn't finish, however widget shows up after reloading page.

#### To discuss

* Unable to add breadcrumb on the custom page, cannot find a way to define custom breadcrumbs without manually defining html tags.
see [./../src/IssuesBundle/Resources/views/Issue/collaboratedRecently.html.twig](./../src/IssuesBundle/Resources/views/Issue/collaboratedRecently.html.twig)
