<?php

class pfaBench {
    /**
     * @return void
     * @Revs(1000)
     * @Iterations(5)
     */
    public function bench_pfa() {
        $strtoupper = p(strtoupper(...));
        $strtoupper('hello')();
    }

    /**
     * @return void
     * @Revs(1000)
     * @Iterations(5)
     */
    public function bench_native() {
        $strtoupper = fn($str) => fn() => strtoupper($str);
        $strtoupper('hello')();
    }
}