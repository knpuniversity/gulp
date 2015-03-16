# Watch for Changes

This *all* looks fun, until you realize that every time you change your Sass
file, you have to run Gulp again. That's not going to work - Gulp needs to
run automagically when a file changes.

Well, there's magic to handle this called `watch()`, and for once, it comes
native with Gulp itself. 

## Creating a Second Task

But first, let's create a second task, which we can do just by using `gulp.task()`
like before. Let's move the guts of the `default` task into this new one:

[[[ code('22fec233ff') ]]]

Hey! Now we have 2 tasks: `sass` and `default`. To prove it, we can run
`gulp sass` and it does all our good stuff.

For now, when I run the `default` task, I just want that to run the `sass`
task. To do this, replace the function callback with an array of task names.
So, `['sass']`:

[[[ code('62035aad64') ]]]

And actually, you *can* still have a callback function - now it would be
the third argument. Gulp will run all the "dependent tasks" first, then call
your function. Handy!

So if we just run `gulp`, it runs the `sass` task first. 

## Adding the watch Task

We're clearly dangerous, so add a third task called `watch`. The name of
the task isn't important, but this fancy `gulp.watch` function is. Copy
the `*.scss` pattern and pass it to `watch()`. This tells it to watch for
changes in *any* of these files. The moment it sees something, we want it
to re-run the `sass` task. So put that as the second argument:

[[[ code('816321d815') ]]]

Isn't that nice? Find your terminal and try out `gulp watch`. It runs, but
then hangs and waits. Go to the browser and refresh. Things look totally
normal. Now, go into `styles.scss`. Channel your inner-designer. Let's change
the dinosaur names to be a majestic vermillion.

Back to the browser! Refresh! That's some nice vermillion. In the background,
evil self-aware robots, I mean, the `watch` function, was doing our job for
us and re-running the `sass` task. Change that color back to black and refresh.
Instantly back to boring. 

## Configuration Variables

But we *are* starting to have some duplication - I've got the `app/Resources/assets/...`
path in 2 places. This is just normal JavaScript, so let's create a variable
called `config`. Hey! I'll even add the equals sign. Let's store some paths
on here, like `assetsDir` set to `app/Resources/assets` and `sassPattern`
set to `sass/**/*.scss`:

[[[ code('d6c3c1e982') ]]]

Now we can just use these variables - classic programming! So,`config.assetsDir`,
a `/` in the middle, then `config.sassPattern`. Put that in both places:

[[[ code('db8c2c7629') ]]]

To stop the `watch` task, type `ctrl+c`. I'll run `gulp sass` to make sure
I didn't break anything. It's happy with the config! 

## Making the default Task Useful

Whenever we start working on a project, we'll want to run the `sass` task
to initially process things and *then* `watch` for future stuff. Hmm, what
if we made the `default` task do this for us? Add `watch` to the array:

[[[ code('e47e13a7fc') ]]]

Now just run `gulp`! It runs `sass` first and then starts watching for changes.
That's really nice.
