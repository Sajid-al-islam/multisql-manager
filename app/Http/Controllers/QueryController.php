<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function showForm()
    {
        return view('query');
    }

    public function convertQueryForm() {
        return view('query-converter');
    }

    public function runQuery(Request $request)
    {
        $mysql_query = $request->input('mysql_query');
        $pgsql_query = $request->input('pgsql_query');
        $result = [];

        if (!empty($mysql_query)) {
            try {
                // Split MySQL queries
                $mysql_queries = explode(';', $mysql_query);
                foreach ($mysql_queries as $query) {
                    $query = trim($query);
                    if (empty($query)) {
                        continue;
                    }
                    // Run query on MySQL
                    DB::connection('mysql')->statement($query);
                    $result['mysql'][] = "Query executed successfully: $query";
                    info('Query execution success Mysql', ['query' => $query]);
                }
            } catch (\Exception $e) {
                $result['mysql'][] = "Error executing query: $query. Error: " . $e->getMessage();
                info('Query execution Failed Mysql', ['error' => $e->getMessage()]);
            }
        }

        if (!empty($pgsql_query)) {
            try {
                // Split PostgreSQL queries
                $pgsql_queries = explode(';', $pgsql_query);
                foreach ($pgsql_queries as $query) {
                    $query = trim($query);
                    if (empty($query)) {
                        continue;
                    }
                    // Run query on PostgreSQL
                    DB::connection('pgsql')->statement($query);
                    $result['pgsql'][] = "Query executed successfully: $query";
                    info('Query execution success Pgsql', ['query' => $query]);
                }
            } catch (\Exception $e) {
                $result['pgsql'][] = "Error executing query: $query. Error: " . $e->getMessage();
                info('Query execution Failed PgSql', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->back()->with('result', $result);
    }

    public function convertQuery(Request $request) {
        $mysqlQuery = $request->input('query');
        $convertedQuery = '';

        try {
            $convertedQuery = $this->convertMysqlToPgsql($mysqlQuery);
        } catch (\Exception $e) {
            $convertedQuery = 'Error: ' . $e->getMessage();
        }

        return redirect()->back()->with('convertedQuery', $convertedQuery);
    }

    private function convertMysqlToPgsql($mysqlQuery)
    {
        // Basic conversions
        $pgsqlQuery = str_replace('`', '"', $mysqlQuery);
        $pgsqlQuery = preg_replace('/AUTO_INCREMENT/i', 'SERIAL', $pgsqlQuery);

        // Convert data types
        $pgsqlQuery = preg_replace('/tinyint(\([0-9]+\))?/i', 'SMALLINT', $pgsqlQuery);
        $pgsqlQuery = preg_replace('/int(\([0-9]+\))?/i', 'INTEGER', $pgsqlQuery);
        $pgsqlQuery = preg_replace('/bigint(\([0-9]+\))?/i', 'BIGINT', $pgsqlQuery);
        $pgsqlQuery = preg_replace('/double(\([0-9,]+\))?/i', 'DOUBLE PRECISION', $pgsqlQuery);

        // Convert CHANGE syntax
        $pgsqlQuery = preg_replace_callback(
            '/ALTER TABLE\s+"?(\w+)"?\s+CHANGE\s+"?(\w+)"?\s+"?(\w+)"?\s+(.+?)(\s+COMMENT\s+\'(.*?)\')?/i',
            function ($matches) {
                $tableName = $matches[1];
                $oldColumnName = $matches[2];
                $newColumnName = $matches[3];
                $columnDef = $matches[4];
                $comment = isset($matches[6]) ? $matches[6] : null;

                // Parse column definition
                preg_match('/(\w+)(?:\(.*?\))?\s*(.*)/i', $columnDef, $defMatches);
                $dataType = $defMatches[1];
                $constraints = $defMatches[2];

                $result = "ALTER TABLE \"$tableName\" ALTER COLUMN \"$oldColumnName\" TYPE $dataType";

                if (stripos($constraints, 'NOT NULL') !== false) {
                    $result .= ";\nALTER TABLE \"$tableName\" ALTER COLUMN \"$oldColumnName\" SET NOT NULL";
                }

                if (preg_match('/DEFAULT\s+(\S+)/i', $constraints, $defaultMatch)) {
                    $defaultValue = $defaultMatch[1];
                    $result .= ";\nALTER TABLE \"$tableName\" ALTER COLUMN \"$oldColumnName\" SET DEFAULT $defaultValue";
                }

                if ($oldColumnName !== $newColumnName) {
                    $result .= ";\nALTER TABLE \"$tableName\" RENAME COLUMN \"$oldColumnName\" TO \"$newColumnName\"";
                }

                if ($comment) {
                    $result .= ";\nCOMMENT ON COLUMN \"$tableName\".\"$newColumnName\" IS '$comment'";
                }

                return $result;
            },
            $pgsqlQuery
        );

        return $pgsqlQuery;
    }
}
