# Sass to CSS

These dinosaurs are our test project! This is a Symfony project, but everything
we'll do translates to any PHP project.

But this look bad - it's messed up. And that's totally my fault. Open up
the base layout - `app/Resources/view/base.html.twig`. I'm including Twitter
Bootstrap, but that's it so far:

[[[ code('6bf4e183b8') ]]]

I *do* have a project-specific CSS file, but it's missing from here. The
problem is that it's not a CSS file at all - it's a Sass file that lives
in `app/Resources/assets`.

Btw, this is where *I've* decided to put my frontend assets, but it doesn't
matter. But *do* notice that this is *not* a public directory.

Gulp's first job will be to turn that Sass file into CSS so I can get my site
to stop looking so ugly.

## Installing `gulp-sass`

With Gulp, we make tasks. But it doesn't do much else. Most things are done
with a plugin. Go back to Gulp's site and click [Plugins](http://gulpjs.com/plugins/)
to find a search for, not 13, but 1373 plugins. The one we want is `gulp-sass`.

First, install it! Copy the `npm install gulp-sass`. But wait! I want you
to add that `--save-dev` because I want this plugin to be added to our `package.json`
file:

```
npm install gulp-sass --save-dev
```

Hey, there it is! When another developer clones the project, they just need
to run `npm install` and it'll download this stuff automatically. Oh, and
the `gulp-sass` plugin preps a sass binary in the background. If you have
any issues installing - especially you wild Windows users - check out the
[node-sass](https://github.com/sass/node-sass) docs.

## The Classic pipe Workflow

Head back to the docs. This is showing a *classic* Gulp workflow. We start
by saying `gulp.src()` to load files matching a pattern. Next, you'll pipe
it through a filter - in our case `sass()` - and pipe it once more to
`gulp.dest()`. That actually writes the finished files.

Let's do it! Be lazy by copying the `require` line and adding it to the top
of our file. Now we'll say `gulp.src`. Let's load all the Sass files that
are in that `sass/` directory - so `app/Resources/assets/sass/**/*.scss`:

[[[ code('94292a1a09') ]]]

That double `**` tells Gulp to look *recursively* inside the `sass` directory
for `.scss` files. That'll let me create subdirectories later if I want.

Now that we've loaded the files, we'll just pipe them through whatever we
need. Use `pipe()` then `sass()` inside of it. Gulp works with streams, so
imagine Gulp is opening up all of our `.scss` files as a big stream and then
passing them one-by-one through the `pipe()` function. So at this point,
all that Sass has been processed. Then finally, we'll pipe that to `gulp.dest()`
and say: "Hey, I want you to dump the finished product to the `web/css/`
directory.":

[[[ code('78b40e208e') ]]]

That's all we need! Head back to the terminal and just type `gulp`:

```bash
gulp
```

Ok, no errors - that seems good. But now we *do* have a `web/css/styles.css`
file. And I know it got processed through Sass because the original is using
a variable.

## Using the Boring CSS File

Now that we have a boring, normal, generated `styles.css` file, let's add
the `link`  tag to our base template. This uses that `asset()` function from
Symfony, but that's not actually doing anything here. The path is relative
to the public directory - `web/` for a Symfony project.

[[[ code('df74fd9950') ]]]

Head back, and refresh! Dinosaurs! That's much better. 

## Ignore Directories in git

Since the `web/css/` directory *only* contains generated files, we don't
need to commit it. If these files are missing, just run gulp! The same goes
for `node_modules` - we can get that by running `npm install`. Silly directories.

So anyways, it'd be great to *not* commit these. So let's open up the `.gitignore`
file and add `/node_modules` and `/web/css`:

[[[ code('dedbc01513') ]]]

Now when I run `git status` again, that stuff's gone!
