# Task Order

Look at the `default` task. The array defines task dependencies: Gulp runs
each of these first, waits for them to finish, and *then* executes the callback
for the task, if there is one. And based on the output, it looks like it
runs them in order: `clean`, then `styles`, then `scripts`.

Is that true?

## There is no Order to Dependent Tasks

Log a message once `fonts` is done:

TODO CODE

And also add a message right when the `watch` task starts:

TODO CODE

The default task defines `fonts` *then* `watch`, and I want to see if that
order matters.

Ok, try it!

```bash
gulp
```

It exploded! It say we're calling `on` on something undefined. This happens
with our code because up in `app.copy`, we're not returning the stream. So
yea, that would be undefined:

TODO CODE

Ok, now try it. It's all out of order! Even though `fonts` is listed
before `watch` in the dependency list, `watch` starts *way* before `fonts`
finishes. In reality, Gulp reads the dependent tasks for `default`, then
starts them all at once. Once they *all* finish, `default` runs.

But what if we *needed* `fonts` to finish before `watch` started? Well, it's
the same trick: add `fonts` as a dependency to `watch`:

TODO CODE

Try it out:

```bash
gulp
```

But surprise! It's still running out of order. Here's the reason: if you're
dependent on a task like `fonts`, that task *must* return a Promise or a
Gulp stream. If it doesn't, Gulp actually has no idea when `fonts` finishes
- so it just runs `watch` right away. So, `return app.copy` from the `fonts`
task, since `app.copy` returns a Gulp stream. Now, Gulp can know when `fonts`
*truly* finishes its work.

TODO CODE

Ok, try it once more:

```bash
gulp
```

There it is! `fonts` finishes, and *then* `watch` starts. And there's one
more thing: Gulp finally prints "Finished 'fonts'" in the right place, *after*
`fonts` does its work.

Why? It's not that Gulp was lying before about when things finished. It's
that Gulp *can't* report when a task finishes *unless* that task returns
a Promise or a Gulp stream. This means we should return one of these from
*every* task.

We don't need the `fonts` dependency, so take it off. And remove the logging:

TODO CODE

So if we should always return a stream or promise, how can we do that for
`styles`? It doesn't have a single stream - it has two that are combined
into the pipeline. We need to wait until *both* of them are finished.

Oh, the answer is so nice: just `return pipeline.run()`. This isn't magic.
I wrote the Pipeline code, and I made `run()` return a Promise that resolves
once *everything* is done. And if you know anything about promises, the guts
should make sense to you. But if you have questions, just ask in the comments.

Make sure we didn't break anything. Yep, it all still looks great. So if
you eventually need to create a task that's dependent on `styles` or `scripts`
finishing first, it'll work.
