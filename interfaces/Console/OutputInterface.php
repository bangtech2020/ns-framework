<?php


namespace interfaces\Console;


use Inhere\Console\Traits\FormatOutputAwareTrait;
use Toolkit\Cli\Style;

/**
 * Class FormatOutputAwareTrait
 *
 * @package Inhere\Console\Traits
 *
 * @method int info($messages, $quit = false)
 * @method int note($messages, $quit = false)
 * @method int notice($messages, $quit = false)
 * @method int success($messages, $quit = false)
 * @method int primary($messages, $quit = false)
 * @method int warning($messages, $quit = false)
 * @method int danger($messages, $quit = false)
 * @method int error($messages, $quit = false)
 *
 * @method int liteInfo($messages, $quit = false)
 * @method int liteNote($messages, $quit = false)
 * @method int liteNotice($messages, $quit = false)
 * @method int liteSuccess($messages, $quit = false)
 * @method int litePrimary($messages, $quit = false)
 * @method int liteWarning($messages, $quit = false)
 * @method int liteDanger($messages, $quit = false)
 * @method int liteError($messages, $quit = false)
 *
 * @method padding(array $data, string $title = null, array $opts = [])
 *
 * @method splitLine(string $title, string $char = '-', int $width = 0)
 * @method spinner($msg = '', $ended = false)
 * @method loading($msg = 'Loading ', $ended = false)
 * @method pending($msg = 'Pending ', $ended = false)
 * @method pointing($msg = 'handling ', $ended = false)
 *
 * @method \Generator counterTxt($msg = 'Pending ', $ended = false)
 *
 * @method confirm(string $question, bool $default = true): bool
 * @method unConfirm(string $question, bool $default = true): bool
 * @method select(string $description, $options, $default = null, bool $allowExit = true): string
 * @method checkbox(string $description, $options, $default = null, bool $allowExit = true): array
 * @method ask(string $question, string $default = '', \Closure $validator = null): string
 * @method askPassword(string $prompt = 'Enter Password:'): string
 */
interface OutputInterface extends \Inhere\Console\Contract\OutputInterface
{
    public function writef(string $format, ...$args);
    public function printf(string $format, ...$args);
    public function writeln($text, $quit = false, array $opts = []);
    public function println($text, $quit = false, array $opts = []);
    public function writeRaw($text, bool $nl = true, $quit = false, array $opts = []);
    public function colored(string $text, string $tag = 'info');
    public function block($messages, string $type = 'MESSAGE', string $style = Style::NORMAL, $quit = false);
    public function liteBlock($messages, string $type = 'MESSAGE', string $style = Style::NORMAL, $quit = false);
    public function title(string $title, array $opts = []);
    public function section(string $title, $body, array $opts = []);
    public function aList($data, string $title = '', array $opts = []);
    public function multiList(array $data, array $opts = []);
    public function mList(array $data, array $opts = []);
    public function helpPanel(array $config);
    public function panel(array $data, string $title = 'Information panel', array $opts = []);
    public function table(array $data, string $title = 'Data Table', array $opts = []);
    public function progressTxt(int $total, string $msg, string $doneMsg = '');
    public function progressBar($total, array $opts = []);
    public function dump(...$vars);
    public function json(
        $data,
        bool $echo = true,
        int $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    public function prints(...$vars);

}
