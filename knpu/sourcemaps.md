# Sourcemaps

Check out the source `style.scss` file. We're giving an element a fancy cursive
font on lines 4 and 5. And that's what makes the `Dinosaurs` text look so
awesome. 

But if we inspect that element, it says the font is coming from `styles.css`
line 2. Dang it! The browser is looking at the final, processed file. And
that means that debugging CSS is going to be an absolute nightmare.

What I *really* want is the debugger to be smart enough to tell me that this
font is coming from our `styles.scss` file at line 4. Such myserious magic
goodness exists, and it's called a sourcemap.

## Using gulp-sourcemaps

Like with everything, this works via a plugin. Head back to the plugins page
and search for [gulp-sourcemaps](https://www.npmjs.com/package/gulp-sourcemaps).
There's the one I want!

Step 1 is always the same - install with `npm`. So:

```bash
npm install gulp-sourcemaps --save-dev
```

Awesome! Next, copy the `require` statement and put that on top:

[[[ code('3f0c8c6b11') ]]]

This plugin is great. First, activate it *before* piping through any filters
that may change which line some code lives on. So, *before* the `sass()` line,
use `pipe()` with `sourcemaps.init()` inside. Then after all those filters
are done, pipe it again through `sourcemaps.write('.')`:

[[[ code('19cdef1c93') ]]]

Time to try things! At the terminal, run `gulp`... and hope for the best!

```bash
gulp
```

Cool - no errors. And *now*, the generated `styles.css` has a neighboring
file: `styles.css.map`! That's what the `.` did - it told Gulp to put the
map file *right* in the same directory as `styles.css`, and the browser knows
to look there.

Time to efresh the page again. Inspect the element again. *Now* it says the
font comes from `styles.scss` on line 4. This is a *huge* deal guys. We can
do whatever weird processing we want and not worry about killing our debugging.

## Sourcemaps Support

Of course, this all works because `gulp-sourcemaps` and `gulp-sass` work
together like super friends. If you look at the `sourcemaps` docs, they have
a link on their wiki to a list of *all* superfriend gulp plugins that play
nice with it. We'll use a couple of these later. 
