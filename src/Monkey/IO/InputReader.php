<?php

declare(strict_types=1);

namespace Monkey\Monkey\IO;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Throwable;

use function is_scalar;

final readonly class InputReader
{
    public function __construct(
        private InputInterface $input,
        private OutputInterface $output,
        private QuestionHelper $questionHelper,
    ) {
    }

    public function readLine(string $prompt = '➜ '): string | false
    {
        $question = new Question("<prompt>{$prompt}</prompt> ");

        try {
            $answer = $this->questionHelper->ask($this->input, $this->output, $question);

            if (!is_scalar($answer)) {
                return false;
            }

            return (string)$answer;
        } catch (Throwable) {
            return false;
        }
    }
}
