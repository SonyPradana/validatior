<?php

use Validator\Rule;
use Validator\Validator;

it('can change supported language', function () {
    $val = new Validator(['test' => null]);

    // change lang before run validation
    $val
        ->lang('id')
        ->field('test')->required()
    ;

    expect($val->get_error())->toBe(['test' => 'Bagian Test harus diisi']);
});

it('change supported language after run validator doesn\'t perform anything', function () {
    $val = new Validator(['test' => null]);

    $val->field('test')->required();
    $err = $val->get_error();
    // change lang after run validation
    $val->lang('id');

    expect($err)->toBe(['test' => 'The Test field is required']);
});

it('can create costume error', function () {
    Rule::set_error_message('required', '{field} can\'t be null');

    $val = new Validator(['tets' => null]);
    $val->test->required();

    expect($val->get_error())->test->toEqual('Test can\'t be null');
});

it('can create costume error (containt \'not\' method)', function () {
    Rule::set_error_message('required', '{field} can\'t be null');

    $val = new Validator(['test' => 'null']);
    $val->test->not()->required();

    expect($val->get_error())->test->toEqual('Test can\'t be null');
});

it('can create costume error multy', function () {
    Rule::set_error_messages([
        'required' => '{field} can\'t be null',
        'min_len'  => '{field} less that 2',
    ]);

    $val = new Validator([
        'tets' => null, 'test2' => 'abc',
    ]);
    $val->test->required();
    $val->field('test2')->min_len(4);

    expect($val->get_error())->toMatchArray([
        'test'  => 'Test can\'t be null',
        'test2' => 'Test2 less that 2',
    ]);
});
