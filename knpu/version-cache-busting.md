# Versioning to Bust Cache

Hey, we've got a nice setup! With `gulp` running, if we update any Sass files,
this `main.css` gets regenerated. But there's just one problem: when we deploy
an updated `main.css`, how can we bust browser cache so our existing visitors
get the new stuff?

To solve this easily, we could go into the template, add a `?v=` to the end,
then manually increment this on each deploy. Of course, I'll *definitely*
forget to do this, so let's find a better way with Gulp.

## Introducing gulp-rev

Search for the plugin `gulp-rev`, as in "revision". Open up its docs. This
plugin does one thing: you point it at a file - like `unicorn.css` - and
it changes the name, adding a hash on the end. That hash is based on the
contents, so it'll change whenever the file changes. Here's the kicker: if
you can *somehow* make your template *automatically* point to whatever the
latest hashed filename is, you've got instant cache-busting. Every time you
deploy with update, your CSS file will have a new name.

And getting this all to work will be easier than you think.

Copy the install line and get that going:

```bash
npm install --save-dev gulp-rev
```

Now, head to `gulpfile.js`. Remember, we're using `gulp-load-plugins`, so
we don't need the `require` line. In `addStyle`, add the new `pipe()` right
*before* the sourcemaps are dumped so that both the CSS file *and* its map
are renamed. Inside, use `plugins.rev()`:

[[[ code('db0ff93f61') ]]]

Ok, let's see what this does. I'll clean out `web/css`:

```bash
rm -rf web/css/*
```

Now run `gulp`:

```bash
gulp
```

Go check out `web/css`. Instead of `main.css` and `dinosaur.css`, we have
`main-50d83f6c.css` and `dinosaur-32046959.css` And conveniently, the maps
got renamed too - so our browser will still find those.

But you probably also see the problem: the site is broken! We're still including
the old `main.css` in our layout.

## Dumping the rev-manifest.json File

We can't just update `base.html.twig` to use the new hashed name because
it would re-break every time we changed the file. What we need is a map that
says: "Hey, `main.css` is actually called `main-50d83f6c.css`." If we had
that, we could use it inside our PHP code to rewrite the `main.css` in the
base template to hashed version automatically. When the hashed name updates,
the map would update, and so would our code.

And of course, the `gulp-rev` people thought of this! They call that map
a "manifest". To get `gulp-rev` to create that for us, we need to ask it
*really* nicely. At the end, add another `pipe()` to `plugins.rev.manifest()`
and tell that *where* we ant the manifest. Let's put it next to our assets
at `app/Resources/assets/rev-manifest.json`:

[[[ code('6259490c4f') ]]]

As you'll see, this file doesn't need to be publicly accessible - our PHP
code just needs to be able to read it.

There's one more interesting step: `pipe()` this into `gulp.dest('.')`:

[[[ code('35c35846eb') ]]]

What?

So far, we've always had one `gulp.src()` at the top and one `gulp.dest()`
at the bottom, but you can have more. Our first `gulp.dest()` writes the
CSS file. But once we pipe to `plugins.rev.manifest()`, the Gulp stream changes.
Instead of being the CSS file, the manifest is now being passed through the
pipes. So the last `gulp.dest()` just writes that file relative to the root
directory.

Let me show you. Stop gulp and restart:

```bash
gulp
```

There's our `rev-manifest.json` file:

```json
{
    "main.css": "main-50d83f6c.css"
}
```

That's our map from `main.css` to its actual filename right now. It *is*
missing `dinosaur.css`, but we'll fix that in a second.

### Fixing the manifest base directory

But there's another problem I want to tackle first. In a second, we're going
to put JavaScript paths into the manifest too. So I *really* need this to
have the full public path - `css/main.css` - instead of just the filename.

So why does it *just* say `main.css`? Because when we call `addStyle()`,
we pass in *only* `main.css`. That means `main.css` is passed to `concat()`
and that becomes the path that's used by `gulp-rev`.

The fix is easy! Inside `concat()`, update it to `css/` then the filename.
That changes the filename that's inside the Gulp stream. To keep the file
in the same spot, just take the `css/` out of the `gulp.dest()` call:

[[[ code('88d10de9d4') ]]]

So nice: those two pipes work together to put the file in the same spot.
Run `gulp` again:

```bash
gulp
```

Now, the `rev-manifest.json` has the `css/` prefix we wanted:

```json
{
    "css/main.css": "css/main-50d83f6c.css"
}
```

### Merging Manifests

So why the heck doesn't my `dinosaur.css` show up here? The `addStyle()`
function is called twice: once for `main.css` and once for `dinosaur.css`.
But the second time, since the manifest file is already there, it does nothing.
*Unless*, you pass an option called `merge` and set it to `true`:

[[[ code('5386f63141') ]]]

Let's see if this fixed it! Re-run `gulp`:

```bash
gulp
```

Yes! The hard part is done - this is a perfect manifest file:

```json
{
    "css/main.css": "css/main-50d83f6c.css",
    "css/dinosaur.css": "css/dinosaur-32046959.css"
}
```

### Making the link href Dynamic

Phew! We're in the homestretch - the Gulp stuff is done. The only thing left
is to make our PHP use the manifest file to change `css/main.css` to something
else.

Since I'm in Twig, I'm going to invent a new filter called `asset_version`:

[[[ code('fa008b040d') ]]]

Let's put the logic behind this! I already created an empty Twig extension
file we can use:

[[[ code('a47d88902f') ]]]

And I told Twig about this in my `app/config/services.yml` file:

[[[ code('90ee7e9309') ]]]

So, this Twig extension is ready to go! All we need to do is register this
`asset_version` filter, which I'll do inside `getFilters()` with
`new \Twig_SimpleFilter('asset_version', ...)` and we'll have it call a method
in this class called `getAssetVersion`:

[[[ code('00c8816e08') ]]]

Below, I'll add that function. It'll be passed the `$filename` that we're
trying to version. So for us, `css/main.css`.

Ok, our job is simple: open up `rev-manifest.json`, find the path, then return
its versioned filename value. The path to that file is `$this->appDir` - I've
already setup that property to point at the `app/` directory - then
`/Resources/assets/rev-manifest.json`:

[[[ code('6d1ad6320f') ]]]

With the power of TV, I'll magically add the next few lines. First, throw
a clear exception if the file is missing. Next, read the file, decode the
JSON, and set the map to an `$assets` variable. Since the manifest file has
the original filename as the key, let's throw one more exception if the file
isn't in the map. And finally, return that mapped value!

[[[ code('d362df0433') ]]]

So we give it `css/main.css` and it gives us the hashed filename.

Let's give it a shot! Take a deep breath and refresh. Victory! Our beautiful
site is back - the hashed filename shows up in the source.

Ok ok, let's play with it. Open `layout.scss` and give everything a red background.
The Gulp watch robots are working in the background, so I immediately see
a brand new hashed `main.css` file in `web/css`. But will our layout automatically
update to the new filename? Refresh to find out. Yes! The new CSS filename
pops up and the site has this subtle red background. 

Go back and undo that change. Things go right back to green. Oh, and we do
have one other CSS file on the dino show page. It *should* be giving us a
little more margin below the T-Rex, but it's 404'ing. We need to make it
use the versioned filename.

So, open up `show.html.twig` and give it the `asset_version` filter:

[[[ code('3800968093') ]]]

Refresh - perfect! No 404 error, and are button can get a little breathing
room from the T-Rex. It took a little setup, but congrats - you've got automatic
cache-busting.

## Don't commit the manifest

But should we commit the `rev-manifest.json` file to source control? I'd
say no: it's generated automatically by Gulp. So, finish things off by adding
it to your `.gitignore` file:

[[[ code('5d7070fcf7') ]]]
