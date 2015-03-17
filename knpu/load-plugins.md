# gulp-load-plugins

Gulp plugins are like busy little elves, so you'll want to use a lot
of them. Of course, that means you'll probably also have a ton of these
awesome-looking require statements on top. Ok, they're really not a big deal,
but if you want get rid of some of them, you can... by using, um, another
plugin! Just go with it - you'll see how it works.

This one is called [gulp-load-plugins](https://www.npmjs.com/package/gulp-load-plugins).
First thing is first: copy it's perfect installation statement:

```bash
npm install --save-dev gulp-load-plugins
```

Next up, copy the `require` line and paste it in:

[[[ code('9d931ebf0a') ]]]

It's got something the others don't have - some parenthesis at the end. This
automatically loads *all* available gulp plugins and sets each on a property
of the `plugins` variable. That means we can comment out - or just remove -
all the require statements except for this one and the one for gulp itself.

Below, just prefix everything with `plugins.`. So, we'll have `plugins.util`.
Actually, the property name is the second part of the plugin's name. So,
`gulp-util` is added to the `util` property. `gulpif` becomes `plugins.if`,
`plugins.sourcemaps` - I'll copy that because I'm getting lazy - then `plugins.sass`,
`plugins.concat` and `plugins.minifyCss`, because `minify-css` is changed
to lower camelcase. Then I'll finish up the rest of them:

[[[ code('51623da2cd') ]]]

I'll clear out the require lines entirely so we can really enjoy things.
But now the burning question is: did I break anything? Go back and run `gulp`:

```bash
gulp
```

Hey, no errors! So if this shorter syntax feels cool to you, go for it.
If you hate the magic, no big deal - keep those requires.
