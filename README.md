
# Laravel Model State Machine

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bvtterfly/model-state-machine.svg?style=flat-square)](https://packagist.org/packages/bvtterfly/model-state-machine)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/bvtterfly/model-state-machine/run-tests?label=tests)](https://github.com/bvtterfly/model-state-machine/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/bvtterfly/model-state-machine/Check%20&%20fix%20styling?label=code%20style)](https://github.com/bvtterfly/model-state-machine/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bvtterfly/model-state-machine.svg?style=flat-square)](https://packagist.org/packages/bvtterfly/model-state-machine)

This package adds support for creating state machines for attributes on Laravel Eloquent Models.

## Requirements

- PHP 8.1 or higher
- Laravel 9.x or higher

## Installation

You can install the package via composer:

```bash
composer require bvtterfly/model-state-machine
```

## Usage

For example, we have a blog system, and our blog posts have three statuses: draft, pending, and published. When we are writing a post, it's in the draft status. Whenever we finish writing our blog posts, we schedule them for publication in the future, which changes the status of the post to pending. Once the post is published, the status changes to published.

The simplest backed enum that holds the states of a blog post status is:

```php
use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\DefaultState;

enum PostState: string
{
    #[DefaultState]
    #[AllowTransitionTo(self::PENDING)]
    case DRAFT = 'draft';

    #[AllowTransitionTo(self::PUBLISHED)]
    case PENDING = 'pending';
    
    case PUBLISHED = 'published';
    
}
```
Here's what the Blog post model would look like:

```php
class Post extends Model
{
    use HasStateMachine;

    protected $casts = [
      'status' => PostState::class  
    ];
    
    
    public function getStateMachineFields(): array
    {
        return [
            'status'
        ];
    }
    
}
```
A model can have as many state machine fields as you want,
You need to add them to the list using the `getStateMachineFields` method.

> Since State machine loads state configuration from string backed enums, You need to cast state machine fields to correlated state enums in your model.

Now, You can get your state machine:

```php
$stateMachine = $post->getStateMachine('status')
```

### Get All states

You can use the `getAllStates` method, which return collection of the all available states:

```php
$stateMachine->getAllStates();
```

### Get All allowed transitions

You can use the `getStateTransitions` method, which return collection of available transitions for current/default state

```php
$stateMachine->getStateTransitions();
```

> If the state field is null and the state configuration doesn't have a default state (field in unknown state), It will throw an exception.

If you want to get available transitions for a state, You can pass it to the method:

```php
$stateMachine->getStateTransitions(PostState::PENDING);
// or $stateMachine->getStateTransitions('pending');
```

### Using transitions

To use transitions, call the `transitionTo` method on the state field as follows:
```php
$stateMachine->transitionTo(PostState::PUBLISHED);
// or $stateMachine->transitionTo('published');
```

> You can pass array as a second argument to the `transitionTo` method for additional data that you'll need in your actions and transitions.

### State Actions

You can add actions to run if a state changes to a state. In the above example, Maybe we want to send a tweet and send email to subscribers when the post is published.

We can do this using the `#[Actions]` attribute. Here's how our `PostState` would look like:

```php
enum PostState: string
{
    #[DefaultState]
    #[AllowTransitionTo(self::PENDING)]
    case DRAFT = 'draft';

    #[AllowTransitionTo(self::PUBLISHED)]
    case PENDING = 'pending';

    #[Actions(SendTweetAction::class, SendEmailToSubscribers::class)]
    case PUBLISHED = 'published';

}
```

Actions are classes that implements `Bvtterfly\ModelStateMachine\Contracts\StateMachineAction`:

```php
class SendTweetAction implements StateMachineAction
{

    public function handle(Model $model, array $additionalData): void
    {
        // send tweet...
    }
}
```

> Your actions may also type-hint any dependencies they need on their constructors. All actions are resolved via the Laravel service container, so dependencies will be injected automatically.

### Transition Actions

In addition to state actions, maybe you want to run actions only when a specific state transit to another state.

You can pass array of actions as second argument to `#[AllowTransitionTo]`.

In the above example, If we want to send a notification to the admin when the post status change to the pending, Our `PostState` would look like this:

```php
enum PostState: string
{
    #[DefaultState]
    #[AllowTransitionTo(self::PENDING, [SendNotificationToAdmin::class])]
    case DRAFT = 'draft';

    #[AllowTransitionTo(self::PUBLISHED)]
    case PENDING = 'pending';

    #[Actions(SendTweetAction::class, SendEmailToSubscribers::class)]
    case PUBLISHED = 'published';
}
```

> Transition Actions run **before** State Actions

### Action With Validation

When using transitions, you can pass additional data as a second argument, and this data will pass to all actions. So, It's necessary to validate this data before running actions.

Validators are actions that implement `Bvtterfly\ModelStateMachine\Contracts\StateMachineValidation`.

In above example, We want to send notification to admin when post status changes to pending:

```php
$stateMachine->transitionTo(PostState::PENDING, [
    'message' => '...'
]);
```

Here's how our `SendNotificationToAdmin` action would look like: 

```php
class SendNotificationToAdmin implements StateMachineAction, StateMachineValidation
{

        public function validate(Model $model, array $additionalData): void
    {
        $validator = validator($additionalData, [
            'message' => 'required',
        ]);
        
        if ($validator->fails()) {
            // throw exception
        }
    }

    public function handle(Model $model, array $additionalData): void
    {
        // send notification...
    }
}
```

### Custom transition Classes

This package comes with a default transition class that save new state after running State & Transition Actions. If you need to do more than just changing to the new state, you can use transition classes.


Custom transition are classes that implements `Bvtterfly\ModelStateMachine\Contracts\StateTransition`.

For example, We want to store the `user_id` of who changes the status of a post to `pending` status in the post model:

```php
class DraftToPending implements StateTransition
{
    public function commitTransition(
            BackedEnum|string $newState,
            Model $model,
            string $field,
            array $additionalData
        ): void {
            $model->{$field} = $newState;
            $model->causer = $additionalData['user_id']
            $model->save();
        }
}

```
You can pass this class as a third argument to the `#[AllowTransitionTo]`.

Then, Our `PostState` would look like this:

```php
enum PostState: string
{
    #[DefaultState]
    #[AllowTransitionTo(self::PENDING, [SendNotificationToAdmin::class], DraftToPending::class)]
    case DRAFT = 'draft';

    #[AllowTransitionTo(self::PUBLISHED)]
    case PENDING = 'pending';

    #[Actions(SendTweetAction::class, SendEmailToSubscribers::class)]
    case PUBLISHED = 'published';
}
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ari](https://github.com/bvtterfly)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
