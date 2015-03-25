# Page Specific CSS

RAWR! Um, click on the T-rex. Here, we get personal with Mr Tyranosaur. His
big image has a class called `dino-img-show` that's not used *anywhere* else
on this site. But the CSS behind this lives in `styles.scss`. And that means
we're including it on *every* page.

That's a bummer! I need the flexibility to have *page-specific* CSS files,
in addition to my one big giant layout CSS file.

First, move this stuff into its own Sass file called `dinosaur.scss`. I'll
paste that in there:

[[[ code('66c37ff19e') ]]]

The Gulp watch robots are hard at work in the background. *AND*, they're
looking for *every* `.scss` file in that directory. That means, when I refresh,
I still have the `dino-img-show` styling. See, it's adding all that margin
between the image and the button. We have 3 Sass files, but it's all still
compiling into one big `main.css`.

Here's the goal: configure Gulp to give us two files: `main.css` made from
`styles.scss` and `layout.scss` and `dinosaur.css` made from this new one.
Then, we can include `dinosaur.css` only on this show page. RAWR!

## Include specific Files in main.css

First, let's make `main.css` only include two of these files. Update `gulp.src()`.
Instead of a pattern, we can pass it an array. We'll feed it `sass/layout.scss`
and then `sass/styles.scss`:

[[[ code('192ba615a6') ]]]

Ok gulp, restart yourself!

```bash
gulp
```

And refresh! The margin is gone - the stuff from `dinosaur.scss` is no longer
included. Ok, good start!

## Isolating the Styles Pipeline

Now, how can we get Gulp to do *all* of this same logic, but dump out a new
`dinosaur.css` file.

Start by creating a new variable called `app`. We'll use this as a place
to store our own custom functions - including a nice new one called `addStyle`.
Give this two arguments - the `paths` we want to process and the final `filename`
to write. Next, copy the guts of the `sass` task into `addStyle` and make
it dynamic: fill in `paths` on top, and `filename` instead of `main.css`:

[[[ code('035e20c44e') ]]]

Can you guys see what's next? In the `sass` task, we'll call `app.addStyle()`,
keep the two paths, comma, then `main.css`:

[[[ code('a5660481d8') ]]]

I like it! Let's make sure we didn't break anything. Restart gulp and then
refresh the page:

```bash
gulp
```

## Processing a Second CSS File

Yep, still looks ok! Now let's put that margin back!

To do that, we can just call `addStyle()` again. Copy the first `addStyle`
and make it load only `dinosaur.scss`. Oh, and give it a different output
name - how about `dinosaur.css`:

[[[ code('35a41ef9e2') ]]]

Ok! Hit ctrl+c then restart Gulp:

```bash
gulp
```

I'm hoping we'll uncover a new `dinosaur.css` when we dig inside the `web/css`
directory. Yes! And it's got just the stuff form its one source file.

## Updating the Template

The last step has nothing to do with Gulp: we need to add a `link` tag to
this one page. In Twig, I'll override my block `stylesheets`, call the
`parent()` function to keep what's in my layout, then create a normal `link`
tag that points to `css/dinosaur.css`:

[[[ code('14512fecb2') ]]]

That should do it. Go back and refresh that page! The margin is back, thanks
to our page-specific CSS. So when you need to add some new CSS, you don't
*need* to throw it in your one, gigantic main CSS file. If it's specific
to a page or section, compile a new CSS file just for that. It's all really
simple.
