<?php

namespace App\Http\Controllers;

use App\Models\AgentPerformance;
use App\Models\CallLog;
use App\Models\TargetAchievement;
use Illuminate\Http\Request;

class QueryBotController extends Controller
{

    public function handleQuery(Request $request)
    {
        $query = $request->input('query');
        
        if (preg_match('/calls|logs/i', $query)) {
            return $this->handleCallLogs($query);
        } elseif (preg_match('/agent performance|performance|progress/i', $query)) {
            return $this->handleAgentPerformance($query);
        } elseif (preg_match('/targets|achievements|salled|statics/i', $query)) {
            return $this->handleTargetsAchievements($query);
        } elseif (preg_match('/hello|hi| |/i', $query)) {
            return response()->json(['message' => 'Welcome to Query Bot, How can I help you?'], 200);
        } else {
            return response()->json(['error' => 'Invalid query'], 400);
        }
    }

    public function handleCallLogs($query)
    {
        if (preg_match('/missed/i', $query)) {
            $callLogs = CallLog::where('call_status', 'missed')->latest()->take(10)->get();
        }
        elseif (preg_match('/answered/i', $query)) {
            $callLogs = CallLog::where('call_status', 'answered')->latest()->take(10)->get();
        }else{
            $callLogs = CallLog::latest()->get();
        }

        // the call_duration data are intigers, ex.:(5, 123, 10, ...,). So I am converting them into time periods like so: (00:05, 02:03)
        foreach ($callLogs as $log) {
            $hours = intdiv($log->call_duration, 60);
            $minutes = $log->call_duration % 60;
            $log->call_duration = sprintf('%02d:%02d', $hours, $minutes);
        }
        $callLogs['count'] = $callLogs->count();
        return response()->json(['callLogs' => $callLogs]);
    }

    public function handleAgentPerformance($query)
    {
        if (preg_match('/agent id (\d+)/i', $query, $matches)) {
            $agentId = $matches[1];
            $performance = AgentPerformance::where('agent_id', $agentId)->latest()->get();

            foreach ($performance as $data) {
                $hours = intdiv($data->average_call_duration, 60);
                $minutes = $data->average_call_duration % 60;
                $data->average_call_duration = sprintf('%02d:%02d', $hours, $minutes);
            }

            if ($performance->isEmpty()) {
                return response()->json(['error' => 'No performance data found for the given agent ID'], 404);
            } else {
                return response()->json(['performance' => $performance]);
            }
        } else {
            $performance = AgentPerformance::latest()->take(10)->get();
            return response()->json(['Performance' => $performance], 200);
        }
    }

    public function handleTargetsAchievements($query)
    {
        if (preg_match('/agent id (\d+)/i', $query, $matches)) {
            $agentId = $matches[1];
            $targets = TargetAchievement::where('agent_id', $agentId)->latest()->take(10)->get();
            if ($targets->isEmpty()) {
                return response()->json(['error' => 'No targets data found for the given agent ID'], 404);
            } else {
                return response()->json(['targets' => $targets]);
            }
        }else{
            $targets = TargetAchievement::latest()->get();
        }
        return response()->json(['targets' => $targets]);
    }


    public function getCallLogs()
    {
        $callLogs = CallLog::latest()->get();
        return response()->json(['callLogs' => $callLogs]);
    }

    public function getAgentPerformance() {
        $targets = TargetAchievement::latest()->get();
        return response()->json(['targets' => $targets]);
    }
    public function getTargetsAchievements() {
        $performance = AgentPerformance::latest()->get();
        return response()->json(['Performance' => $performance], 200);
    }

}
