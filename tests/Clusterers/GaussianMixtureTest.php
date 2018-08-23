<?php

namespace Rubix\Tests\Clusterers;

use Rubix\ML\Estimator;
use Rubix\ML\Persistable;
use Rubix\ML\Probabilistic;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Clusterers\GaussianMixture;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class GaussianMixtureTest extends TestCase
{
    protected $estimator;

    protected $dataset;

    public function setUp()
    {
        $this->dataset = Labeled::restore(dirname(__DIR__) . '/iris.dataset');

        $this->estimator = new GaussianMixture(2, 1e-3, 100);
    }

    public function test_build_clusterer()
    {
        $this->assertInstanceOf(GaussianMixture::class, $this->estimator);
        $this->assertInstanceOf(Probabilistic::class, $this->estimator);
        $this->assertInstanceOf(Persistable::class, $this->estimator);
        $this->assertInstanceOf(Estimator::class, $this->estimator);
    }

    public function test_estimator_type()
    {
        $this->assertEquals(Estimator::CLUSTERER, $this->estimator->type());
    }

    public function test_make_prediction()
    {
        $this->estimator->train($this->dataset);

        $results = $this->estimator->predict($this->dataset);

        $clusters = array_count_values($results);

        $this->assertEquals(50, $clusters[0], '', 3);
        $this->assertEquals(50, $clusters[1], '', 3);
    }

    public function test_predict_untrained()
    {
        $this->expectException(RuntimeException::class);

        $this->estimator->predict($this->dataset);
    }
}
