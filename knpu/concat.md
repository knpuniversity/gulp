# Combining (concat) Files

We only have one CSS file, and gosh, that's just not very realistic. So,
let's create a second one. Call it, `layout.scss`. To keep the dinosaurs
happy, let's give the body a background - a nice dark green background:

[[[ code('186b921f1b') ]]]

Ya know, because they're, sorta, dark green.

Go back and refresh! Ok, it doesn't work *quite* yet. But hey! We *do* have
the `watch` task running in the background, and it's looking for *any* `.scss`
file in that directory. So we should at *least* have a new `layout.css` file.
Ah, there it is.

If we want to keep things simple, we can just add another `link` tag in the
base template to use this:

[[[ code('6653748e07') ]]]

Perfect!

## Using gulp-concat

But I don't *want* to make my users download a ton of CSS files. I want
to combine them all into a single file. There's a plugin for that. This time,
it's called [gulp-concat](https://github.com/wearefractal/gulp-concat).

Let's do our thing. Step 1: install this via npm. Hit `ctrl+c` to stop
watch, then:

```bash
npm install gulp-concat --save-dev
```

While the npm robots work on that for us, let's go back and copy the `require`
statement:

[[[ code('7f6eac65c6') ]]]

The `gulp.src()` function loads a stream of many files. The new `concat()`
function combines those streams into just 1 file. He's such minimalist.

Let's do this right after `sass()` - `pipe()`, then `concat()`. And pass
it the filename it should create - `main.css`:

[[[ code('3afbed8973') ]]]

Make sure you keep this between the `sourcemaps` lines because we're smashing
multiple files into one, and that'll change the line numbers and source files
for everything. But sourcemaps will keep track of all of that for us. Good
job sourcemaps!

Empty out the `web/css` directory before testing things: those shouldn't
be generated anymore. Now, try `gulp`:

```bash
gulp
```

And hey, now we've got just one beautiful `main.css` file and its map. It's
got the CSS from both our source files. Don't forget to celebrate by going
back to your base template and turning those 2 link tags into 1.

Refresh to try it. Of *course* it works, but now with just 1 CSS file...
other than my Bootstrap and font stuff. But the *real* test is whether the
sourcemap still works. Inspect on our awesome cursive. Yep, it knows things
are coming from `style.scss` on line 4. Right on!
