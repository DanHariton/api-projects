<?php

declare(strict_types=1);

namespace App\DataFixtures\Faker;

use Faker\Provider\Base as BaseProvider;

final class ProjectProvider extends BaseProvider
{
    /** @var string[] */
    protected static array $projectNames = [
        'Quantum Entanglement Simulator',
        'Gravitational Wave Detector',
        'Fusion Reactor Efficiency Model',
        'Dark Matter Exploration',
        'High-Energy Particle Accelerator',
        'Superconducting Materials Study',
        'Relativity and Time Dilation Experiment',
        'Plasma Dynamics Simulation',
        'Wave-Particle Duality Analyzer',
        'Neutrino Oscillation Research'
    ];

    /** @var string[] */
    protected static array $projectDescriptions = [
        'A simulation tool to model and visualize quantum entanglement between particles.',
        'A research project aimed at developing a detector for measuring gravitational waves with high precision.',
        'An analytical model for improving the efficiency of nuclear fusion reactors.',
        'A theoretical and experimental approach to understanding the properties of dark matter.',
        'Design and optimization of a high-energy particle accelerator for subatomic research.',
        'An experimental study of new superconducting materials at extremely low temperatures.',
        'A relativistic study of time dilation effects in high-speed travel scenarios.',
        'Simulating plasma behavior under extreme magnetic fields for fusion applications.',
        'An investigation into the dual nature of light and matter through quantum mechanics.',
        'A study on neutrino oscillations and their implications for fundamental physics.'
    ];

    public function projectName(): string
    {
        return static::randomElement(static::$projectNames);
    }

    public function projectDescription(): string
    {
        return static::randomElement(static::$projectDescriptions);
    }
}
