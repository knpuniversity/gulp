# Bower Components out of web

Great news! We've minified and combined all our CSS into just one file. Oh,
except for the Bootstrap CSS! We have two good options. First, we could
point the link to a public Bootstrap CDN. Or second, we can cram the bootstrap
styling right into `main.css`. Let's do that - it's a lot more interesting
anyways.

## Bower!

Bootstrap is installed thanks to Bowser. Um, I mean bower. [Bower](http://bower.io/)
is the Composer for frontend assets, like Bootstrap or jQuery. It's an alternative
to downloading the files and committing them to your repo.

Bower reads from, surprise!, a `bower.json` file:

[[[ code('10770634e9') ]]]

And when I created the project, I also added a `.bowerrc` file. This told
bower to download things into this `web/vendor` directory:

[[[ code('7da8a93577') ]]]

That made them publicly accessible.

But now that we have the muscle of Gulp, I want to complicate things. Change
this to be `vendor/bower_components`:

[[[ code('3cf8d26340') ]]]

That'll put these files *outside* of the public directory, which at first,
will cause some issues. Delete that old stuff:

```bash
rm -rf web/vendor
```

Head to the terminal and ctrl+c out of gulp. Now, run `bower install`:

```bash
bower install
```

If you don't have the `bower` command, just check out their docs. It's installed
globally via npm, exactly like gulp.

Done! Now, in my `vendor/` directory I have a beautiful `bower_components`
folder.

## Adding CSS Files to our Gulp Styles Stream

But even I wanted to have 2 separate link tags in my layout, I can't:
Bootstrap no longer calls the `web/` directory home. So, get rid of its
link tag.

In `gulpfile.js`, let's try to fix things! I'll start by adding a new configuration
variable called `bowerDir`, because it's going to be really common to refer
to things in that directory. Set it to `vendor/bower_components`:

[[[ code('8b65d009fc') ]]]

If you open that directory, you can see where the `bootstrap.css` file
lives. Notice, it's *not* a Sass file - just regular old CSS. There actually
*is* a Sass version of Bootstrap, and you can totally use this instead
if you want to control your Bootstrap variables.

So, can we push plain CSS files through our Sass-processing `addStyle` function?
Sure! Let's add `config.bowerDir` then `/bootstrap/dist/css/bootstrap.css`:

[[[ code('78a7bd2da4') ]]]

And we don't even need to worry about getting the min file, because we're
already taking care of that. This file *will* go through the `sass` filter.
But that's ok! It'll just look like the most boring Sass file ever.

Head back and run `gulp`:

```bash
gulp
```

And *now*, `main.css` starts out with glorious Bootstrap code. And it would
be minified if I had passed the `--production` flag.

Our site should still look great. So refresh. Yep, it's just like before,
but with one less pesky CSS file to download.

## Renaming the Task to styles

And before we keep going, I think we can make another improvement. Our task
is called `sass`. Let's change this to `styles`, because it's job is *really*
to process styles, whether those are CSS or Sass files.

[[[ code('6c4ed160a0') ]]]

We also need to change that name on the `default` task. Ooops, and before
we re-start Gulp, also change the name on the `watch` task to `styles`:

[[[ code('9a0df77178') ]]]
 
Ok, *now* we can try things. ctrl+c to stop Gulp, and re-start it:

```bash
gulp

```

Yes, no errors! And the site still looks Tri-tastic! See what I did there?
