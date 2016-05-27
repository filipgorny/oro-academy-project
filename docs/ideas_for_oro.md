Please note that all below opinions are personal and are written by the person who is just getting familiar with Oro Bundles.
These are not Oro team's statements and it is not an official document, please treat it as a notes of ideas written during
the development process.

* Validation of yml may be helpful.
When for example configuring data grids, I am able to add `where` key under `source`. I wanted to add it to the query
but I did mistake in indentation. The Oro WorkflowBundle did not tell me that this option is in the wrong place.

* Emphasis Interfaces over inheritance and code structure
Some bundles require user to inherit on the specified classes or place class under specified folder. This should
be less tight and should not force user to design his code in imposed way. Instead, usage of interfaces should be
the way to connect into the API's of bundles, and instead of folder structure the classes implementing those interfaces
should be easy to register/unregister in the configuration files.

* Merge documentation
Some crucial parts of the documentation are either on the Oro website or at github. Merging these two together may
give developer more confidence and shorten up time for getting familiar with Oro Bundles.
Also more end to end examples should be included. Ie. in the workflows, I was not able to find how to read
the attribute data I have set in one of steps, it was not included in the docs and the only way to find
out was to debug the code. It should be mandatory to provide documentation for reading state, when there is a document
on how to modify it.

* Browserify instead of require-js
Currently used way to load javascript files is generating massive traffic of many HTTP requests. I would suggest to merge
javascript files on the server side and serve it less requests. Gulp/grunt/webpack usage may be a good way to do that.

* Form handlers may be replaced with interface implementation
Form handlers is the idea from FOS that is not official symfony way and even the authors went away from this solution.
Adding an interface would be a better solution to use when developing with SoapBundle.



