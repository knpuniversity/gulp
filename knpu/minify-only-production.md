# Minify only in Production

A lot of times, I'll *only* minify my CSS when I'm deploying. Then locally,
keep the whitespace for debugging. This matters a lot less now that we
have `sourcemaps`, but I still want a way to control the minification.

Here's the goal: when I run `gulp`, I want it to *not* minify. But if I run
`gulp --production`, I *do* want it to minify. Got it?

## Installing gulp-util

To parse out that flag, the [gulp-util](https://github.com/gulpjs/gulp-util)
is the perfect tool. For some reason, it's missing from the Gulp plugins
page, so just Google for it.

First, get it installed! Of course, that's:

```
npm install gulp-util --save-dev
```

Next, grab the `require` line and paste it in top. I'm going to change the
variable to just be `util`:

[[[ code('b554869597') ]]]

## Reading the --production Flag

We can use `util` to figure out if the `--production` flag is passed.
Run `console.log(util.env.production)`:

[[[ code('759a1d69aa') ]]]

Let's experiment and see what that does! Go back and just run `gulp`:

```bash
gulp
```

Ok, it dumps out `undefined`. Now run it with `--production`:

```bash
gulp --production
```

Hey, it's `true`! Ok, one step closer. 

Let's add a new config value called `production` and set that to `!!util.env.production`:

[[[ code('228a0dc263') ]]]

Those two exclamations turn `undefined` into a proper false. It's a clever
way to clean things up!

## Conditional Statements in pipe()

Now all we need to do is add an `if` statement around the `minifyCSS()` line,
right? Right? Um, actually it's not so easy.

The issue is with how the gulp stream works: you can't just stop in the middle
of these pipes and keep going after an if statement. It just doesn't work
like you'd think.

Instead, let's add some `if` logic right inside the `pipe()` that says if
`config.production`, let's `minifyCSS()`, else, run this through a filter
called `util.noop`. Woops, I should actually type `util`:

[[[ code('17750112b5') ]]]

This filter does *absolutely nothing*. Woo! Isn't that brilliant? So if we're
not in production, the `pipe()` still happens, but no changes are made. 

Moment of truth. First, just run `gulp`:

```bash
gulp
```

In this case, `main.css` is *not* minified. Go back, hit `ctrl+c`, and add
the `--production`:

```bash
gulp --production
```

Goodbye whitespace! And in case you want to do anything else different in
production, you've got a handy config value.
