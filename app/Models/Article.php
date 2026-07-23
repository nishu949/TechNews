<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'views',
        'reading_time'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
        'reading_time' => 'integer',
    ];

    // Relationships
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', 'approved');
    }

    // Boot method
    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title) . '-' . time();
            }
            
            // Calculate reading time on create
            $article->reading_time = $article->calculateReadingTime();
        });

        static::updating(function ($article) {
            if ($article->isDirty('title')) {
                $article->slug = Str::slug($article->title) . '-' . time();
            }
            
            // Recalculate reading time if content changes
            if ($article->isDirty('content')) {
                $article->reading_time = $article->calculateReadingTime();
            }
        });
    }

    // Calculate reading time
    public function calculateReadingTime(): int
    {
        $words = str_word_count(strip_tags($this->content));
        return max(1, ceil($words / 200));
    }

    // Accessors
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getContentHtmlAttribute(): string
    {
        if (!$this->content) {
            return '';
        }
        
        $converter = new \League\CommonMark\CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        
        return $converter->convert($this->content)->getContent();
    }

    public function getReadingTimeAttribute($value): int
    {
        if ($value) {
            return $value;
        }
        return $this->calculateReadingTime();
    }

    public function getExcerptAttribute($value): string
    {
        if ($value) {
            return $value;
        }
        return Str::limit(strip_tags($this->content), 160);
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return asset('images/default-article.jpg');
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('content', 'LIKE', "%{$term}%")
              ->orWhere('excerpt', 'LIKE', "%{$term}%")
              ->orWhereHas('category', function ($category) use ($term) {
                  $category->where('name', 'LIKE', "%{$term}%");
              })
              ->orWhereHas('tags', function ($tag) use ($term) {
                  $tag->where('name', 'LIKE', "%{$term}%");
              });
        });
    }

    public function scopeByTag(Builder $query, string $tagSlug): Builder
    {
        return $query->whereHas('tags', function ($q) use ($tagSlug) {
            $q->where('slug', $tagSlug);
        });
    }

    public function scopeByCategory(Builder $query, string $categorySlug): Builder
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    // Increment views
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    // Check if user can edit this article
    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}