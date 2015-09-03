# Errors: Call the Plumber

With `gulp` running, when we update a file, the gulp-bots recompile stuff
for us. We're like a factory for CSS.

But there's a problem, a *big* problem if you're like me and mess up a lot.
Add a syntax error in `layout.scss`.

Now check out `gulp`. It exploded! It's not even running anymore. So even
if I fix the error, `gulp` is dead. I'll probably refresh my browser for
30 minutes before I realize that gulp hasn't been running this entire time.
And I'll spend the next 5 minutes composing an angry tweet.

Out of the box, Gulp supports error handlers where you say "Hey, instead
of exploding all crazy, just call this function over here." But instead,
I'll show you a really nice plugin called `gulp-plumber` that'll take care
of this for us.

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
Robots, get back to work!

***TIP
Plumber prevents gulp from throwing a proper error exit code. When building for
production, you may *want* a proper error. If so, try using `plumber()` only in
development:

```javascript
.pipe(gulpif(!util.env.production, plumber()))
```

Thanks to Nicolas Sauveur for the tip!
***
