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
up. But hey, let's no duplicate! Instead, copy that path and reference a
new config option called `revManifestPath`. Up top, I'll actually add that
property to `config`:

[[[ code('03d55cb920') ]]]

Ok! Now just feed that to `del.sync()`:

[[[ code('f4bee59cb3') ]]]

