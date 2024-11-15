
# Laravel Search Package

A Laravel package to provide advanced, modular search functionality using customizable search cases.

## Installation

1. **Install the package** via Composer:
   ```bash
   composer require spekt08/laravel-search
   ```

2. **Publish the configuration file** (if applicable):
   ```bash
   php artisan vendor:publish --provider="spekt08\Search\SearchServiceProvider" --tag=config
   ```

---

## Usage

### 1. **Initialize the Search Service**

The `SearchService` requires a model to operate on. You can instantiate it as follows:

```php
use spekt08\Search\SearchService;

$service = app(SearchService::class, [
    'model' => \App\Models\User::class
]);
```

---

### 2. **Perform a Search**

Call the `search` method with the desired query parameters. For example:

```php
$queries = [
    'name' => 'John',     // Filter users by name
    'created_at' => '2025-01-01', // Filter by creation date
    'order' => 'asc',     // Sort in ascending order
];

$result = $service->search($queries);
```

This will return a **paginated** result set.

---

### 3. **Use with a Resource**

If you are using Laravel API Resources, you can pass the resource class as the second parameter of the `search` method:

```php
use App\Http\Resources\UserResource;

$result = $service->search($queries, UserResource::class);
```

This will transform each result using the provided resource.

---

### 4. **Custom Search Cases**

The package includes three default search cases:
- **AttributeCase**: Filters based on model attributes.
- **OrderCase**: Sorts results by a given column (default is `created_at`).
- **CallbackCase**: Allows adding custom callbacks to modify the query.

You can add custom cases by implementing the `SearchCaseInterface`:

```php
use spekt08\Search\Cases\SearchCaseInterface;
use Illuminate\Database\Eloquent\Builder;

class CustomCase implements SearchCaseInterface
{
    public function searchQuery(Builder $query, array $params): Builder
    {
        if (isset($params['custom_filter'])) {
            $query->where('custom_column', $params['custom_filter']);
        }
        return $query;
    }
}
```

Add the custom case to the service:

```php
$customCase = new CustomCase();
$service->add($customCase);
```

---

### 5. **Configuration Options**

If the package includes a `config/search.php` file, you can customize the default behavior, such as:
- Excluded columns (`not_columns`).
- Default date columns (`date_columns`).

---

## Example

```php
$service = app(SearchService::class, ['model' => \App\Models\Post::class]);

$queries = [
    'title' => 'Laravel',
    'author' => 'Taylor',
    'order' => 'desc',
    'callbacks' => [
        function ($query) {
            $query->where('status', 'published');
        },
    ],
];

$result = $service->search($queries, \App\Http\Resources\PostResource::class);
```

---

## Contributing

Feel free to open issues or submit pull requests for new features, bug fixes, or improvements.

---

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
