# Sourcemaps only in Development

When we deploy, we're going to pass `--production` so that everything is
minified like this. But when I do that, I don't want the sourcemaps to be
there anymore. It's not a *huge* deal, but I'd rather not advertise my source
files.

So now we have the *opposite* problem as before: we want to *only* run the
sourcemaps when we're *not* in production. Add another config value called
`sourcemaps` and set it to be *not* `production`:

[[[ code('7635d6565c') ]]]

Make sense?

We can use this to *only* run the two sourcemaps line if `config.sourcemaps`
is true. Instead of using `util.noop()` again, I want to show you another
plugin called [gulp-if](https://github.com/robrich/gulp-if). Go back and
find it on the plugins page. Let's get this guy installed:

```bash
npm install gulp-if --save-dev
```

Go back and grab the `require` line:

[[[ code('8c3b128e3e') ]]]

The whole point of this plugin is to help with the fact that we can't break
up the pipe chain with if statements. With it, you can add `gulpif()` inside
`pipe()`. The first argument is the condition to test - so `config.sourcemaps`.
And if that's true, we'll call `sourcemaps.init()`. Do the same thing down
for `sourcemaps.write()`: `gulpif`, `config.sourcemaps`, then `sourcemaps.write('.')`:

[[[ code('7ce146c536') ]]]

That pipe chain is off the hook! Go back and run just `gulp`:

```bash
gulp
```

We should see the non-minified version *with* sourcemaps. And that's what
we've got! Now add `--production`:

```bash
gulp --production
```

No more sourcemaps!
