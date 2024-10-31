<?php

namespace Shopbox\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\support\Facades\Hash;
use Shopbox\Models\Zpanel\Country;
use Modules\Customer\Entities\Customer;
use Modules\Product\Entities\Attribute;
use Shopbox\Tenant\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Shopbox\Tenant\Observers\TenantObserver;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\Builder as QueryBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*\DB::listen(function ($sql) {
            dump($sql->sql);
        });*/
		
		
        $this->app->singleton(Manager::class, function() {
            return new Manager();
        });

        $this->app->singleton(TenantObserver::class, function () {
            return new TenantObserver(app(Manager::class)->getTenant());
        });
        
        initializeStore();

        Request::macro('tenant', function () {
            return app(Manager::class)->getTenant();
        });

        // needed because of the TNT search scout package uses methods that are specific from laravel 5.6
        // may be able to remove this if this app gets upgraded OR they fix the issue: https://github.com/teamtnt/laravel-scout-tntsearch-driver/issues/171
        QueryBuilder::macro('joinSub', function ($query, $as, $first, $operator = null, $second = null, $type = 'inner', $where = false) {
            list($query, $bindings) = $this->createSub($query);
            $expression = '('.$query.') as '.$this->grammar->wrap($as);
            $this->addBinding($bindings, 'join');
            return $this->join(new Expression($expression), $first, $operator, $second, $type, $where);
        });

        QueryBuilder::macro('leftJoinSub', function ($query, $as, $first, $operator = null, $second = null) {
            return $this->joinSub($query, $as, $first, $operator, $second, 'left');
        });

        QueryBuilder::macro('createSub', function ($query) {
            if ($query instanceof Closure) {
                $callback = $query;
                $callback($query = $this->forSubQuery());
            }
            return $this->parseSub($query);
        });

        QueryBuilder::macro('parseSub', function ($query) {
            if ($query instanceof self || $query instanceof EloquentBuilder) {
                return [$query->toSql(), $query->getBindings()];
            } elseif (is_string($query)) {
                return [$query, []];
            } else {
                throw new InvalidArgumentException;
            }
        });

        Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, auth()->user()->password);
        });

        Validator::extend('zip_code_format', function ($attribute, $value, $parameters, $validator) {
            $country = new Country;
            $data = array_dot($validator->getData());
            return $country->checkZipCode($data[$parameters[0]], $value);
        });

        /*Validator::replacer('zip_code_format', function ($message, $attribute, $rule, $parameters, $validator) {
            $country = Country::find($validator->getData()['country']);
            return str_replace($message,'Your Zip/postal code is incorrect. It must look like: '.str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $country->zip_code_format))),$message);
        });*/

        Validator::extend('unique_to_store', function ($attribute, $value, $parameters, $validator) {
            return unique_to_store($parameters, $value);
        });

        Validator::extend('unique_email', function ($attribute, $value, $parameters, $validator) {
            return unique_email($parameters, $value);
        });

        Validator::extend('unique_domain', function ($attribute, $value, $parameters, $validator) {
            return unique_domain($parameters, $value);
        });

        Validator::extend('validate_discount', function ($attribute, $value, $parameters, $validator) {
            return validate_discount($parameters, $value);
        });
        
        Validator::extend('pattern_exists', function ($attribute, $value, $parameters, $validator) {
            return pattern_exists($attribute, $parameters, $validator);
        });

        Validator::extend('required_state', function ($attribute, $value, $parameters, $validator) {
            return has_state($parameters, $value);
        });

        Validator::extend('valid_rma', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            return valid_rma($data['return_qty'], $value, $parameters);
        });

        Validator::extend('product_exists', function ($attribute, $value, $parameters, $validator) {
            return product_exists($value, $parameters);
        });

        Validator::extend('product_eligible', function ($attribute, $value, $parameters, $validator) {
           return product_eligible($value);
        });

        Validator::extendImplicit('required_if_variant', function ($attribute, $value, $parameters, $validator) {
           return product_variant($value, $parameters);
        });

        Validator::extend('consignment_exists', function ($attribute, $value, $parameters, $validator) {
            return consignment_exists($value, $parameters);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
