# Publish Fonts to web

Even though I look like lunch to them, I do love dinosaurs. So on the dinosaur
page, I'll show my affection with a little heart icon, using Font Awesome.
In the `h1`, add `<i class="fa fa-heart"></i>`:

[[[ code('5e7ec3bd35') ]]]

When I refresh, no love for my dinosaurs. That's because I don't have the
Font Awesome CSS inside of my project. Let's see if bower can fetch it for
us! At the command line, say `bower install font-awesome --save` - that's
similar to the `--save-dev` option with npm.

When that's done, we can find it in `vendor/bower_components/font-awesome`.
*And* or `bower.json` file has a new entry in it:

[[[ code('213589a858') ]]]

Thanks bower!

## Including the font-awesome.css

Our first job is to get that `font-awesome.css` into our site. We know how
to do that - just add it to our `main.css` file. I'll copy the bootstrap
line then update it to `font-awesome/css/font-awesome.css`. Done.

[[[ code('f3cd748145') ]]]

Refresh! Hearts for dino friends! So we're done right?

Well, actually, it *shouldn't* work. In the inspector, I've got 404 errors
for the fontawesome font files. The only reason the heart shows up is that
I *happen* to have the fontawesome font file installed on my computer. But
this will be a broken heart for everyone else in the (Jurrassic) world.

Font Awesome goes up one level from its CSS and looks for a `fonts/` directory.
Since its code lives in `main.css`, it goes up one level and looks for `fonts/`
right at the root of `web/`. If you bring in the Font Awesome Sass package,
you *can* control where it's looking. But even then, we have a problem. The
FontAwesome `fonts/` directory is buried deep inside `vendor/bower_components`.
Somehow, we need to copy this stuff into `web/`.

## The copy Function

Copying files around sounds pretty handy. So lets go straight to making a
new function `app.copy` with two arguments `srcFiles` and `outputDir`. We'll
read in some source files and copy them to that new spot:

[[[ code('c22987f8f4') ]]]

Great news! You already know how to copy files in Gulp! Just create the normal
pipe chain, but without any filters in the middle: `gulp.src(srcFiles)`,
then pipe that directly to `gulp.dest(outputDir)`:

[[[ code('42d1827ef8') ]]]

So nice!

## Publish those Fonts!

Next, add a new task called `fonts`. The job of this guy will be to "publish"
any fonts that we have into `web/`. Right now, it's just the FontAwesome
stuff. Use the `app.copy()` and for the path, start with `config.bowerDir`.
I'll scroll up so we can see the path. Now, `font-awesome/fonts/*` to grab
everything. For the target, just `web/fonts`:

[[[ code('0bb9868b3e') ]]]

We'll want this to run with our default task, so add `fonts` down there:

[[[ code('d611d7a0c4') ]]]

But I don't really care about watch - it's not like we'll be actively changing
these files.

Ok, restart Gulp!

```bash
gulp
```

Yes, it *is* running the `fonts` task. Inside `web/`, we have a shiny
new - and populated - `fonts/` directory. And since FontAwesome is looking
right here for them, we can refresh, and those nasty 404's are gone.

## Don't Commit the Fonts!

We don't want to commit this new `web/fonts` directory - it's got generated
files just like the `css/` and `js/` folders. To avoid the humiliation of
accidentally adding them to your repo, add this path to `.gitignore`.

[[[ code('b23d0725a3') ]]]

Thats it! And if there's anything else you need to move around, just use
our handy `app.copy()`.
