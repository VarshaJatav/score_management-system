<template>
  <div class="p-4 bg-black text-white min-h-screen flex flex-col items-center">
    <div class="flex justify-between w-full text-lg font-bold mb-6">
      <span>OFFICIAL TIMEKEEPER</span>
      <span class="text-xl">CASIO NVL</span>
      <span>NEPAL VOLLEYBALL LEAGUE</span>
    </div>

    <div class="grid grid-cols-3 gap-4 w-full max-w-6xl">
      
      <div class="bg-gray-900 p-4 rounded-xl shadow-lg flex flex-col items-center">
        <div class="text-center">
          <img v-if="match.home_team?.logo" :src="match.home_team.logo" class="h-16 mx-auto mb-2" />
          <h2 class="text-2xl font-bold text-green-400">{{ match.home_team?.name }}</h2>
        </div>

        <div class="mt-6 w-full">
          <h3 class="font-semibold text-center border-b border-gray-600 pb-1">TEAM LINEUP</h3>
          <ul class="mt-2 space-y-1 text-center">
            <li v-for="p in homeLineup" :key="p.position_number">
              {{ p.jersey }} - {{ p.name }}
            </li>
          </ul>
        </div>

        <div class="mt-6 w-full">
          <h3 class="font-semibold text-center border-b border-gray-600 pb-1">TEAM STATS</h3>
          <ul class="mt-2 space-y-1 text-center">
            <li>Kills: {{ homeStats.kills }}</li>
            <li>Digs: {{ homeStats.digs }}</li>
            <li>Aces: {{ homeStats.aces }}</li>
            <li>Service: {{ homeStats.service }}</li>
            <li>Assists: {{ homeStats.assists }}</li>
            <li>Blocks: {{ homeStats.blocks }}</li>
          </ul>
        </div>
      </div>

      <div class="bg-gray-800 p-4 rounded-xl shadow-lg flex flex-col items-center">
        <h2 class="text-xl font-bold mb-4">SET {{ match.active_set }}</h2>

        <div class="flex gap-12 items-center mb-6">
          <div class="text-5xl font-bold text-green-400">{{ currentSet.home_score }}</div>
          <div class="text-lg">VS</div>
          <div class="text-5xl font-bold text-blue-400">{{ currentSet.away_score }}</div>
        </div>

        <div class="w-full">
          <table class="w-full text-center border border-gray-600">
            <thead>
              <tr class="bg-gray-700">
                <th class="p-1">Set</th>
                <th>{{ match.home_team?.short_name }}</th>
                <th>{{ match.away_team?.short_name }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="s in match.sets" :key="s.number" class="border-t border-gray-600">
                <td class="p-1">{{ s.number }}</td>
                <td>{{ s.home_score }}</td>
                <td>{{ s.away_score }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mt-4 text-lg font-bold">
          Sets Won: {{ match.home_sets_won }} - {{ match.away_sets_won }}
        </div>
      </div>

      <div class="bg-gray-900 p-4 rounded-xl shadow-lg flex flex-col items-center">
        <div class="text-center">
          <img v-if="match.away_team?.logo" :src="match.away_team.logo" class="h-16 mx-auto mb-2" />
          <h2 class="text-2xl font-bold text-blue-400">{{ match.away_team?.name }}</h2>
        </div>

        <div class="mt-6 w-full">
          <h3 class="font-semibold text-center border-b border-gray-600 pb-1">TEAM LINEUP</h3>
          <ul class="mt-2 space-y-1 text-center">
            <li v-for="p in awayLineup" :key="p.position_number">
              {{ p.jersey }} - {{ p.name }}
            </li>
          </ul>
        </div>

        <div class="mt-6 w-full">
          <h3 class="font-semibold text-center border-b border-gray-600 pb-1">TEAM STATS</h3>
          <ul class="mt-2 space-y-1 text-center">
            <li>Kills: {{ awayStats.kills }}</li>
            <li>Digs: {{ awayStats.digs }}</li>
            <li>Aces: {{ awayStats.aces }}</li>
            <li>Service: {{ awayStats.service }}</li>
            <li>Assists: {{ awayStats.assists }}</li>
            <li>Blocks: {{ awayStats.blocks }}</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  matchId: {
    type: [Number, String],
    required: true
  }
})

const match = ref({
  home_team: {},
  away_team: {},
  sets: [],
  active_set: 1,
  home_sets_won: 0,
  away_sets_won: 0
})

const homeStats = ref({})
const awayStats = ref({})
const homeLineup = ref([])
const awayLineup = ref([])
const currentSet = ref({ home_score: 0, away_score: 0 })

let intervalId = null

function updateFromMatchResource(data) {
  loadMatch()
  match.value = {
    home_team: data.teams.a,
    away_team: data.teams.b,
    sets: data.sets.map(s => ({
      number: s.set_number,
      home_score: s.a,
      away_score: s.b,
      is_completed: s.is_completed
    })),
    active_set: data.current_set,
    home_sets_won: data.teams.a.sets_won,
    away_sets_won: data.teams.b.sets_won
  }

  homeStats.value = data.teams.a.totals
  awayStats.value = data.teams.b.totals
  homeLineup.value = data.teams.a.lineup
  awayLineup.value = data.teams.b.lineup

  const set = match.value.sets.find(s => s.number === match.value.active_set)
  currentSet.value = set
    ? { home_score: set.home_score, away_score: set.away_score }
    : { home_score: 0, away_score: 0 }
}


async function loadMatch() {
  try {
    const { data } = await axios.get(`/api/scoreboard/${props.matchId}`)
    updateFromMatchResource(data)
  } catch (error) {
    console.error("Failed to load scoreboard:", error)
  }
}

onMounted(() => {
  loadMatch()
console.log("Echo object:", window.Echo) 
  window.Echo.channel(`match.${props.matchId}`)
    .listen('.App\\Events\\ScoreUpdated', (e) => updateFromMatchResource(e.match))
    .listen('TeamStatsUpdated', (e) => updateFromMatchResource(e.match))
    .listen('LineupUpdated', (e) => updateFromMatchResource(e.match))

})

onUnmounted(() => {
  if (intervalId) clearInterval(intervalId)
})
</script>
