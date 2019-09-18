# Add a plugin

### Swagger-UI relies on plugins for all the good stuff.

Plugins allow you to add
- `statePlugins`
  - `selectors` - query the state
  - `reducers` - modify the state
  - `actions` - fire and forget, that will eventually be handled by a reducer. You *can* rely on the result of async actions. But in general it's not recommended
  - `wrapActions` - replace an action with a wrapped action (useful for hooking into existing `actions`)
- `components` - React components
- `fn` - commons functions

To add a plugin we include it in the configs...

```js
SwaggerUI({
  url: 'some url',
  plugins: [ ... ]
})
```

Or if you're updating the core plugins.. you'll add it to the base preset: [src/core/presets/base.js](https://github.com/swagger-api/swagger-ui/blob/master/src/core/presets/base.js)

Each Plugin is a function that returns an object. That object will get merged with the `system` and later bound to the state.
Here is an example of each `type`

```js
// A contrived, but quite full example....

export function SomePlugin(toolbox) {

  const UPDATE_SOMETHING = "some_namespace_update_something" // strings just need to be uniuqe... see below

  // Tools
  const fromJS = toolbox.Im.fromJS // needed below
  const createSelector = toolbox.createSelector // same, needed below

  return {
    statePlugins: {

      someNamespace: {
        actions: {
          actionName: (args)=> ({type: UPDATE_SOMETHING, payload: args}), // Synchronous action must return an object for the reducer to handle
          anotherAction: (a,b,c) => (system) => system.someNamespaceActions.actionName(a || b) // Asynchronous actions must return a function. The function gets the whole system, and 