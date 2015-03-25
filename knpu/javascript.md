# Minify and Combine JavaScript

Now for the most thrilling part: JavaScript. Create a new `js` directory
inside `app/Resources/assets` and a new file - let's call that `main.js`.

Ok, let's start with a jQuery document ready block and then log a movie quote
that combines two of my *favorite* things: UNIX and dinosaurs. Thank you
Michael Crichton. Let's add something visual to the bottom navbar too. A subtle
warning, if you will.

[[[ code('ded01e3389') ]]]

With such a cool JavaScript file, I can't wait to include it on my site.
But, I can't just go add a `script` tag - `main.js` isn't in a public directory.
Gulp, halp!

## scripts Task in Gulp

Create a new task called `scripts`:

[[[ code('d8580c1ff5') ]]]

Inside here, we're going to do almost the exact same stuff we do with our
CSS. So let's copy the inside of `addStyle` and paste it in our task. Now
we need to make a bunch of small adjustments.

First, get rid of a few things, like the `sass` filter and `minifyCss`. We
*will* minify in a second, but we need to use a different tool.

Second, in `src()`, start with `config.assetsDir` then `/js/main.js`. We
also need jQuery. That already lives inside the `bower_components` directory.
So above `main.js`, add `config.bowerDir` - that new config sure is coming
in handy - then `/jquery/dist/jquery.js`.

And to put the cherry on top, change the `dest()` to `web/js` and give `concat()`
a filename - how about `site.js`.

[[[ code('291e0aeaaf') ]]]

Perfect. We're reusing all that good stuff we learned earlier!

Exit out of `gulp` with ctrl+c then just try running `gulp scripts`:

```bash
gulp scripts
```

Now in `web/`, we have a new `site.js` file *and* its map. So with almost
no work, JS processing is alive!

## Updating watch and default

Let's update the `watch` task so that the evil robots *also* rebuild things
whenever we update a JS file. Copy the existing line. For the path, have it
look for anything in the `js` directory recursively - so `js/**/*.js`. And
when that happens, run `scripts`:

[[[ code('0e85b882df') ]]]

Add this to the `default` task too:

[[[ code('b7f1e2a7d2') ]]]

Let's exercise the *whole* system. Go back and just run `gulp`:

```bash
gulp
```

Great, it runs `scripts` and then the watch waits. To test that, go back
and add a period to `main.js`, save and you can see that `scripts` ran again
automatically.

After all this, we have a *real* `site.js` file in a public directory! We
can now use that to add a boring `script` tag to our layout. Near the bottom,
add the script tag. For the path, I'm using the `asset()` function from
Symfony, but it doesn't really do anything. Say, `js/site.js`:

[[[ code('a6cd2ad8d4') ]]]

Ok, refresh! Go down to the bottom, yes! There's our cryptic "Life finds a way"
message. And if I bring up the debugger, I can see the UNIX line in the console.
The whole thing works great.

## Don't Commit web/js

Now, nobody likes it when you commit generated files. And that's what lives
in `web/js`. So, make sure you add this path to your `.gitignore`:

[[[ code('58d646b482') ]]]

## Minify with gulp-uglify

One last challenge: `site.js` is *not* minified yet. Dinosaurs hate whitespace,
so let's fix that. For our styles, we used that `minifyCss` plugin. For JS,
we'll use one called `gulp-uglify`. Grab its perfect install statement,
stop gulp, and get that downloading:

```
npm install --save-dev gulp-uglify
```

We don't need the `require` line though, because `gulp-plugins-require` takes
care of that for us. We can go straight to work. Copy the `minifyCss` line so
that we have the cool `--production` flag behavior. Paste it and change things
say `plugins.uglify()`:

[[[ code('3326152a0e') ]]]

Cool! Try it out first with just `gulp`:

```bash
gulp
```

This should give us the non-minified version. Excellent! Now ctrl+c that
and try again with `--production`:

```bash
gulp --production
```

That file has been uglified! That kind of sounds like a Harry Potter spell.
Uglify!

## Multiple JavaScript Files

Oh yea, back to dinosaurs. Eventually, we may want some page-specific
JavaScript files.

So, add a new `app.addScript` function with `paths` and `filename` arguments.
Copy all of the `scripts` task, paste it, and update `src()` with `paths`
and `site.js` with `filename`:

[[[ code('8c1bfa72f3') ]]]

Back in the `scripts` task, we can just call that function. So, `app.addScript`,
keep those paths, then pass `site.js`:

[[[ code('5f1e38dfaf') ]]]

Delete `site.js` and try it!

```bash
gulp
```

On hey, welcome back `site.js`. Now when you need a page-specific JavaScript
file, just add another `addScript` call here. Feeling powerful?

What else can you do? Well, if you're into CoffeeScript, you can grab a plugin
for that and mix it right in. Do whatever you want.
