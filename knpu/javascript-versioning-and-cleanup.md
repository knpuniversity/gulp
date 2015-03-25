# JavaScript Versioning and Cleanup

After a while, this versioning magic is going to clutter up our `web/css`
directory. That's really no problem - our `rev-manifest.json` always updates
to point to the right one. So one good solution to all this clutter is to
ignore it! We don't need to perfect *everything*.

But since you're still listening, let's clean that up.

Grab an npm library called `del` to help us with this. So:

```bash
npm install del --save-dev
```

This is *not* a gulp plugin, so we need to go to the top and add
`var del = require('del')`:

[[[ code('5bee016625') ]]]

This library does what it sounds like - it helps delete files.

We need a way to clean up our generated files. That sounds like a perfect
opportunity to add a new task! Call it `clean`:

[[[ code('ba9b748851') ]]]

Inside here, we want to remove *everything* that gulp generates. The first
thing I can think of is the `rev-manifest.json` file. We don't *need* to
clear this, but if you delete a CSS file, its last map value will still live
here. So to keep only *real* files in this list, we might as well remove
it entirely once in awhile.

To do that, use `del.sync()`. This means that our code will wait at this
line until the file is actually deleted. The path to the manifest is further
up. But hey, let's not duplicate! Instead, copy that path and reference a
new config option called `revManifestPath`. Up top, I'll actually add that
property to `config`:

[[[ code('03d55cb920') ]]]

Ok! Now just feed that to `del.sync()`. The other things we need to clean
up are `web/css/*`, `web/js/*` and `web/fonts/*`:

[[[ code('f4bee59cb3') ]]]

Great! Now, we *could* run this manually, but instead of doing that, add this
to the beginning of the `default` task. So when I run gulp, it'll start by
cleaning things up.

[[[ code('b7812aaa76') ]]]

We have almost 10 things in `web/css`. Gulp, take out the trash.

```bash
gulp
```

My, what a tidy directory.

## Versioning JavaScript

There's one more improvement we need to make. The bottom of every page reminds
us that "Life finds a way". This message is added via JavaScript. And that
code lives inside the *one* JS file: `site.js`. It deserves versioning too.

Go steal the `plugins.rev()` line from `addStyle()` and put that right before
the sourcemaps of `addScript`. Woopsies, I must have gotten a little too
excited with my indentation. Next, grab the last 2 lines that dump the one
manifest file:

[[[ code('2a94ae0bf8') ]]]

We also need to correct the paths so that the manifest has the `js/` directory
part in the filename. So, to `concat()`, I'll add `js/`, then put just `web`
for the first `dest()` call:

[[[ code('c3f7947ef0') ]]]

Ok, restart gulp!

```bash
gulp
```

Now check out `js/`. Very good - we've got `site-` the hash. And open up
`rev-manifest.json` - it's got one new entry for this. The setup for CSS
and JS files is no different - the maps are all getting put into the same
file.

If we refresh now, we of course don't see our message. We're still
including `site.js`. But since we already made our Twig plugin, we can
pipe that path through `asset_version`. And that'll take care of everything.

## Where Now?

I *really* hope you now love Gulp as much as I do! There's more you can do,
like using `gulp-autoprefix` to add the annoying vendor-specific CSS prefixes
for you. This setup is something that works well for me, but go ahead and
make it your own. If you're doing some cool things with Gulp, let us know
in the comments.

Seeya next time!
