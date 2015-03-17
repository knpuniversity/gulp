# Minify

Yep, we're combining multiple files into one. That's pretty cool. But our
`main.css` has an embarrassing amount of whitespace. Gross. Let's minify
this.

Another plugin to the rescue! This one is called `gulp-minify-css`. And yea,
there are *a lot* of plugins that can minify CSS. But this is a good one,
*and* it's a superfriend with the sourcemaps plugin. They've even given us
a nice install line, so I'll copy that, stop my watch task, and get it downloading:

```bash
npm install --save-dev gulp-minify-css
```

Next, go steal the require line and paste it on top:

[[[ code('a6b9b8645a') ]]]

Oh, and let's put a `var` before that.

And once again, we're going to use the trusty `pipe()` function to push things
through `minifyCSS()`:

[[[ code('7a42c52d6a') ]]]

This is looking really nice. Oh, and most of these functions - like `minifyCSS()`
or `sass()`  *do* take some options. So if you need to customize how things
are minified, you can totally do that.

Ok, go back and run gulp!

```bash
gulp
```

And now `main.css` is a single line. BUT, through the power of sourcemaps,
we *still* get the correct `styles.scss` line in the inspector. 
