# Errors: Call the Plumber

With `gulp` running, we know we can update files, and it'll re-compile them.
But there's a problem, a *big* problem if you're like me and mess up a lot.
Add a syntax error in `layout.scss`.

Now check out `gulp`. It exploded! It's not even running anymore. So even
if I fix the error, `gulp` is dead. That means I'll go refresh the browser,
then spend 30 minutes trying to figure out why my CSS changes don't seem
to be doing anything before I realize gulp has been dead the entire time.
This is how angry Twitter rants happen!

Out of the box, Gulp supports error handlers where you say "Hey, instead
of exploding all crazy, just call this function over here." But I want to
show you a really nice plugin called `gulp-plumber` instead.

Go look for [gulp-plumber](https://www.npmjs.com/package/gulp-plumber).
Copy its handy install statement, and paste that to get it downloading.

```bash
npm install --save-dev gulp-plumber
```

Now, usually, this is where we'd go copy the `require` statement. But since
we added `gulp-load-plugins`, we can skip that!

Instead, let's get to work. We need to pipe gulp through this plugin *before*
any logic that might cause an error. So right after `gulp.src`, say
`pipe(plugins.plumber())`:

[[[ code('e178041187') ]]]

And yea, that's it. Go back and get gulp back and running:

```bash
gulp
```

Let's mess up `layout.scss` again! This time, gulp *does* show us the error,
it just doesn't die anymore. How nice! When we fix the error, it recompiles.
That's such a small detail, but if you don't have this, you're not going
to have a good time with gulp.
