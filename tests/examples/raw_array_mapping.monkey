--TEST--
Testing raw array mapping
--FILE--
let rawMap = fn(arr, callback) {
    let iter = fn(arr, accumulated) {
        if (len(arr) == 0) {
            return accumulated;
        }

        return iter(slice(arr, 1), push(accumulated, callback(first(arr))));
    };

    return iter(arr, []);
};

let foo = rawMap([1, 2, 3, 4], fn(x) { x * 2 });

print foo;
--EXPECT--
[2, 4, 6, 8]
