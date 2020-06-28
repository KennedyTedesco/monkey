--TEST--
Testing raw array mapping
--FILE--
let map = fn(arr, callback) {
    let iter = fn(arr, accumulated) {
        if (len(arr) == 0) {
            return accumulated;
        }

        return iter(slice(arr, 1), push(accumulated, callback(first(arr))));
    };

    return iter(arr, []);
};

let foo = [1, 2, 3, 4];
foo = map(foo, fn(x) {
    return x * 2
});

print foo;
--EXPECT--
[2, 4, 6, 8]
