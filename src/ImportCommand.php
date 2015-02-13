<?php namespace Tlr\LaravelLangTools;

use Illuminate\Support\Facades\View;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lang:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import csv langs to php array files.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$file = $this->laravel['files']->get(
			$this->parsePath( $this->argument('input') )
		);

		$data = $this->hydrateArrays(
			$this->reformArrays(
				$this->parseFile( $file )
			)
		);

		$this->writeLangs( $data, $this->argument('output') );

		$this->line('');
		$this->info('Completed');
	}

	/**
	 * Parse the input file from csv to php arrays
	 *
	 * @param  string $path
	 * @return array
	 */
	public function parseFile( $file )
	{
		$rows = explode(PHP_EOL, $file);

		$output = array();

		// @hack - something weird was happening with php's csv functions.
		// This gets the job done!
		foreach ($rows as $key => $row)
		{
			if ($row)
			{
				$output[$key] = str_getcsv($row);
			}
		}

		return $output;
	}

	/**
	 * Allow for absolute and relative file paths
	 *
	 * @param  string $path
	 * @return string
	 */
	public function parsePath( $path )
	{
		if ( substr($path, 0, 1) == '/' )
			return realpath($path);

		return realpath(base_path( $path ));
	}

	/**
	 * Separate the one dimentional, n-length arrays into n flattened arrays of rows
	 *
	 * The input is csv-like data in rows, with a header row describing the columns
	 * The output is a key-value array of head header to an array with each row's value for it
	 *
	 * The first column is the key, and all subsequent columns are language keys
	 *
	 * @param  array $rows
	 * @return array
	 */
	public function reformArrays( $rows )
	{
		$output = array();

		// get the header row from the input
		$headers = array_splice($rows, 0, 1)[0];

		// $xi = 1 - skip the first column, as it's the lang key
		for ($xi=1; $xi < count($headers); $xi++)
		{
			$lang = array();

			// get the cells for that header
			foreach ($rows as $key => $value)
			{
				// $value[0] is the lang key
				// $value[$xi] is the translation of that key for the $xi'th language column
				$lang[$value[0]] = array_get($value, $xi);
			}

			// add the header and its data to the output array
			$output[$headers[$xi]] = $lang;
		}

		return $output;
	}

	/**
	 * Hydrate a collection of arrays
	 *
	 * @param  array $langs
	 * @return array
	 */
	public function hydrateArrays( $langs )
	{
		$output = array();

		foreach ($langs as $key => $array)
		{
			$output[$key] = $this->hydrateArray( $array );
		}

		return $output;
	}

	/**
	 * Hydrate a single array from flattened dot notation
	 *
	 * @param  array $array
	 * @return array
	 */
	public function hydrateArray( $array )
	{
		$output = array();

		foreach ($array as $key => $value)
		{
			$hydratedValue = $this->unFlatten( explode('.', $key), $value );

			$output = array_merge_recursive( $output, $hydratedValue );
		}

		return $output;
	}

	/**
	 * Unflatten a single key value pair
	 *
	 * @param  array $keys
	 * @param  mixed $value
	 * @return array
	 */
	public function unFlatten( $keys, $value )
	{
		$key = array_shift($keys);

		if ( empty($keys) )
		{
			return array( $key => $value );
		}

		return array( $key => $this->unFlatten($keys, $value) );
	}

	/**
	 * Write the lang files to the given directory
	 *
	 * @param  array $langs
	 * @param  string $output
	 */
	public function writeLangs($langs, $output)
	{
		$path = base_path($output);
		$files = $this->laravel['files'];

		$files->makeDirectory($path, 0777, true, true);
		$this->info('Creating Directories...');

		// loop over each language, creating a directory for each one, and fill it
		// with lang files and lines
		foreach ($langs as $slug => $data)
		{
			$this->info("Unpacking Language: $slug");

			$files->makeDirectory( "{$path}/{$slug}" );

			foreach ($data as $key => $items)
			{
				$this->comment("  - Writing Lang Namespace: $key");

				$files->put(
					"{$path}/{$slug}/{$key}.php",
					View::make('laravel-lang-tools::lang', ['items' => $items])->render()
				);
			}
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			[ 'input', InputArgument::REQUIRED, 'The input file - relative to the project dir.' ],
			[ 'output', InputArgument::OPTIONAL, 'The directory to output to - relative to the project dir.', 'lang' ],
		);
	}
}
