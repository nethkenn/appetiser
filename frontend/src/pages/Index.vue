<template>
	<div class="q-pa-md">
		  <q-page>
			  	<q-card flat bordered class="my-card">
			  	  <q-card-section>
			  	    <div class="text-h6">Calendar</div>
			  	  </q-card-section>
			  	  <q-separator inset />

			  	  <q-card-section>
			  	  	<div class="row">
			  	  		<div class="col-12 col-md-6">
			  	  			<div class="q-pa-md">
			  	  				<q-input v-model="form.eventName" 
			  	  				      label="Event Name" dense 
			  	  					  color="dark"
			  	  					  ref="eventName"
			  	  				      :rules="[val => !!val || 'Field is required']"/>
			  	  			</div>
			  	  			<div class="row q-pa-md q-gutter-lg">
			  	  				<div class="col">
			  	  					<q-input type="date" 
			  	  						v-model="form.dateFrom" 
			  	  						label="From" 
			  	  						stack-label  
			  	  						dense color="dark" 
			  	  						:min="datePicker.minDate" 
			  	  						:max="datePicker.maxDate" 
			  	  					 	 ref="dateFrom"
			  	  						:rules="[val => !!val || 'Field is required']"/>
			  	  				</div>
			  	  				<div class="col">
			  	  					<q-input type="date" 
			  	  						v-model="form.dateEnd"
			  	  						label="To" 
			  	  						stack-label 
			  	  						dense 
			  	  						color="dark" 
			  	  						:min="datePicker.minDate" 
			  	  						:max="datePicker.maxDate" 
			  	  					 	ref="dateEnd"
			  	  						:rules="[val => !!val || 'Field is required']"/>
			  	  				</div>
			  	  			</div>
			  	  			<div class="q-pa-md q-gutter-sm">
			  	  				   <q-checkbox v-model="form.dayType" val="Mon" label="Mon" color="black"/>
			  	  				   <q-checkbox v-model="form.dayType" val="Tue" label="Tue" color="black"/>
			  	  				   <q-checkbox v-model="form.dayType" val="Wed" label="Wed" color="black"/>
			  	  				   <q-checkbox v-model="form.dayType" val="Thu" label="Thu" color="black"/>
			  	  				   <q-checkbox v-model="form.dayType" val="Fri" label="Fri" color="black"/>
			  	  				   <q-checkbox v-model="form.dayType" val="Sat" label="Sat" color="black"/>
			  	  				   <q-checkbox v-model="form.dayType" val="Sun" label="Sun" color="black"/>
			  	  			</div>
			  	  			<div class="q-pa-md">
			  	  				   <q-btn color="dark" size="md" label="Save" @click="saveEvent()"/>
			  	  			</div>
			  	  		</div>
			  	  		<div class="col-12 col-md-6">
			  	  			<div class="text-h6 text-weight-bold">
			  	  				{{calendarMonth}}
			  	  			</div>
			  	  			<q-list bordered separator>
			  	  			    <q-item v-for="(day,index) in calendar" :key="index"
			  	  			    	clickable 
			  	  			    	v-ripple
			  	  			    	:class="{ 'bg-green-11' : day.hasOwnProperty('eventName')}">
			  	  			      <q-item-section>
			  	  			      	{{day.day}} {{day.dayType}}
			  	  			      </q-item-section>
			  	  			      <q-item-section side v-if="day.hasOwnProperty('eventName')">
			  	  			      	{{day.eventName}}
			  	  			      </q-item-section>
			  	  			    </q-item>
			  	  			  </q-list>
			  	  		</div>
			  	  	</div>
			  	  </q-card-section>
			  	</q-card>
		  </q-page>
	</div>
</template>

<script>
export default {
  name: 'PageIndex',
  data: () => ({
  	calendar  : [],
  	monthYear : {
		year : 2018,
		month : 7
  	 },
  	calendarMonth : '',
  	form : {
  		eventName : '',
	  	dateFrom : '',
	  	dateEnd : '',
	  	dayType : []
  	},
  	datePicker : {}
  }),
  methods : {
  	saveEvent()
  	{
  		this.$refs.eventName.validate();
  		this.$refs.dateFrom.validate();
  		this.$refs.dateEnd.validate();

  		if (this.$refs.eventName.hasError || this.$refs.dateFrom.hasError || this.$refs.dateEnd.hasError) 
  		{
  		     this.formHasError = true
  		}
  		else
  		{
	  		axios.post('api/saveEvent', this.form)
		      .then(response => {
					this.getCalendar();
					this.$q.notify({
						 position: 'top-right',
					     message: response.data.message,
					     color: 'green-6',
					     icon : 'check',
					     textColor : 'white'
					   })
		     })
  		}
  	},
  	getCalendar()
  	{
  		axios.post('api/getMonthCalendar', this.monthYear)
	      .then(response => {
	       	this.calendarMonth = response.data.calendarMonth
	       	this.calendar 	   = response.data.calendar
	       	this.datePicker    = response.data.datePicker
	     })
  	}
  },
  mounted () {
   this.getCalendar();
  }
}
</script>
