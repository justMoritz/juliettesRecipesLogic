# Juliettes Recipes Logic

## ABOUT:

Collection of some of the PHP logic used in the complex [Juliettes Recipes](https://www.moritzzimmer.com/juliettesrecipes) project, including some CMS logic.

Code has been slightly changed from actual code for comprehension, and does not use real database names for safety.


## Explanation:

The Entry form for a new recipe will POST to `entry_handle.php`, which does some logic, then redirect to `output.php` performing additions and displaying results

The Edit form for an existing recipe will POST to `editor_handle.php`, which does some logic, then redirect to `output.php` as well for the same reason.

`search.php` and `result.php` represent some of the public display logic used.

