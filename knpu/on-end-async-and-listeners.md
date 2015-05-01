# on('end'): Async and Listeners

Run Gulp! Spoiler alert: Gulp is lying to you. It *looks* like everything
runs in order: clean starts, clean finishes, *then* styles starts. But that's
wrong. The truth is that everything is happening all at once, asynchronously.
And to be fair, Gulp isn't really lying - it actually has *no* idea *when*
each task actually finishes. Well, at least not yet.

Let's find out what's really going on.

## Gulp Streams are like a Promise

Each line in a Gulp stream is asynchronous - like an AJAX call. This means
that before `gulp.src()` finishes, the next `pipe()` is already being called.
In fact, the *whole* function might finish before `gulp.src()` is done.

But we really need each line to run in order. So when you call `pipe()`,
it doesn't run what's inside immediately: it schedules it to be called once
the previous line finishes. The effect is like making an AJAX call, adding
a success listener, then making another AJAX call from inside it.

I wonder then, does the `main.css` file finish compiling before `dinosaur.css`
starts? Does the scripts wait for the styles task to finish? Let's find out.

## Adding on('end') Listeners

Like with AJAX, each line returns something that acts like a Promise. 
That means, for any line, we can write `on` to add a listener for when *this*
specific line *actually* finishes. When that happens, let's
`console.log('start '+filename)`.

Copy this and add another listener to the last line. Change the text to "end":

[[[ code('e46d874548') ]]]

Ok, run gulp!

```bash
gulp
```

Woh! When it said it finished "styles", it really means it was done executing
the `styles` task. But things really finish way later. In fact, they don't
even *start* the process until later. And what's *really* crazy is that `dinosaur.css`
starts *before* `main.css`, even though main is the first style we add.

So, you can't depend on *anything* happening in order. But, what if you need
to?

## Race Condition in the Manifest

There's a bug with our manifest file - a race condition! Ah gross! Because
of the `merge` option, it opens up the manifest, reads the existing keys,
updates one of them, then re-dumps the whole file.

For styles, the manifest file is opened twice for `main.css` and `dinosaur.css`.
If one opens the file before the other finishes writing, when it writes,
it'll run over the changes from the first.

How can we make the first `addStyle` finish before starting the second?

## Using on to Control Order

It turns out the answer is easy. We can attach an `end` listener to any part
of the Gulp stream. Return the stream from `addStyle`. Then in `styles`,
attach an `on('end')` and only process `dinosaur.css` once the previous
call is finished:

[[[ code('0b9e3a6448') ]]]

I know, it's ugly - we'll fix it, I promise! But let's see if it works:

```bash
gulp
```

Perfect! `main.css` starts and ends. *Then* `dinosaur.css` starts.

## Using the Pipeline

This is the key idea. But the syntax here is terrible. If we have 10 CSS
files, we'll need 10 levels of nested listeners. That's not good enough.

To help fix this, I'll paste in some code I wrote:

[[[ code('5fc367f125') ]]]

It's an object called Pipeline - and it'll help us execute Gulp streams one
at a time. It has a dependency on an object called `q`, so let's go install
that:

```bash
npm install q --save-dev
```

On top, add `var Q = require('q')`

[[[ code('13bd0148c8') ]]]

To use it, create a `pipeline` variable and set it to `new Pipeline()`. Now,
instead of calling `app.addStyle()` directly, call `pipeline.add()` with
the same arguments. Now we can move `dinosaur.css` out of the nested callback
and use `pipeline.add()` again. Woops, typo on pipeline!

[[[ code('2986000001') ]]]

`pipeline.add` is basically queuing those to be run. So at the end, call
`pipeline.run()` and pass it the actual function it should call:

[[[ code('2ec176a960') ]]]

Behind the scenes, the Pipeline is doing what we did before: calling `addStyle`,
waiting until it finishes, then calling `addStyle` again.

Try it!

```bash
gulp
```

Cool - we've got the same ordering.

### Pipelining scripts

Ok! Let's add this pipeline stuff to scripts. First, clean up my ugly debug
code. Make sure you actually `return` from `addScript` - we need that stream
so the `Pipeline` can add an `end` listener.

[[[ code('727e913699') ]]]

Down in `scripts` work your magic! Create the `pipeline` variable, then
`pipeline.add()`. And, `pipeline.run()` to finish:

[[[ code('4692ae8385') ]]]

Ok, try it!

```bash
gulp
```

Good, no errors! Use the Pipeline if you like it. But either way, remember
that Gulp runs everything all at once. You *can* make one entire task wait
for another to finish, but we'll talk about that later.
