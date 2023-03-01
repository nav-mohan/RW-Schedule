console.log("Loading plugin RW-Schedule get-timetable-body.js",RW_URLS['wp_ajax']);

const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday','Saturday','Sunday'];
const segments = [
    "00:00", "00:30",
    "01:00", "01:30",
    "02:00", "02:30",
    "03:00", "03:30",
    "04:00", "04:30",
    "05:00", "05:30",
    "06:00", "06:30",
    "07:00", "07:30",
    "08:00", "08:30",
    "09:00", "09:30",
    "10:00", "10:30",
    "11:00", "11:30",
    "12:00", "12:30",
    "13:00", "13:30",
    "14:00", "14:30",
    "15:00", "15:30",
    "16:00", "16:30",
    "17:00", "17:30",
    "18:00", "18:30",
    "19:00", "19:30",
    "20:00", "20:30",
    "21:00", "21:30",
    "22:00", "22:30",
    "23:00", "23:30"
];

function fetchTimetable(){
    const FORM_GET_TIMETABLE = new FormData();
    FORM_GET_TIMETABLE.set('action','rw_schedule_get_timetable');
    return fetch(RW_URLS['wp_ajax'],
    {
        method:"POST",
        body:FORM_GET_TIMETABLE
    })
    .then((res)=>{
        if(res.ok)
            return res.json();
        else
            throw new Error("Failed to fetch rwshows from wp-admin-ajax API endpoint");
    })
    .catch((error)=>{
        alert(error);
    })
    .then(res_json=>{
        return res_json.data
    })
}

function calculateEventFlexBasis(time1,time2){
    if(time2 == "00:00")
        time2 = "24:00"
    const [hours1, minutes1] = time1.split(':');
    const [hours2, minutes2] = time2.split(':');
    const duration = (hours2 - hours1) + (minutes2 - minutes1)/60;
    const flexBasis = 100*duration/24
    // return 100*Math.round(flexBasis/100,2)
    return flexBasis
}


function renderTimetable(events){
    let column,cell,prevEventEndTime,paddingEvent,dailyCumulativeFlexBasis;
    const timetable = document.getElementById('timetable');
    column = document.createElement('div');
    column.classList.add("column");
    cell = document.createElement('div');
    cell.classList.add('row-header')
    cell.innerText = "Time"
    column.appendChild(cell)
    for (let i = 0; i < segments.length; i+=1) {
        cell = document.createElement('div');
        cell.classList.add('row-header')    
        cell.innerText=segments[i];
        column.appendChild(cell)
    }
    timetable.appendChild(column)

    for (let i = 0; i < days.length; i++) {
        prevEventEndTime = "00:00";
        dailyCumulativeFlexBasis = 0;
        const day = days[i];
        const column = document.createElement('div')
        column.classList.add('column')
        const columnHeader = document.createElement('div')
        columnHeader.classList.add('column-header')
        columnHeader.innerText = day;
        column.appendChild(columnHeader)
        const dailyEvents = events.filter(e=>(e.day == day)).sort((a,b)=>(b.startTime-a.startTime))
        for (let j = 0; j < dailyEvents.length; j++) {
            const event = dailyEvents[j];
            if(event.startTime != prevEventEndTime){
                paddingEvent = {
                    "day": day,
                    "startTime": prevEventEndTime,
                    "endTime": event.startTime,
                    "title": "Playlist"
                };
                const paddingEventFlexBasis = calculateEventFlexBasis(paddingEvent.startTime,paddingEvent.endTime);
                dailyCumulativeFlexBasis += paddingEventFlexBasis;
                cell = document.createElement('div');
                cell.classList.add('event');
                cell.style.flexBasis = `${paddingEventFlexBasis}%`;
                cell.innerHTML = `
                    <span class = "event-time">${paddingEvent.startTime}</span> - 
                    <span class = "event-time">${paddingEvent.endTime}</span> <br> 
                    <span class = "event-title">${paddingEvent.title}</span>`;
                column.appendChild(cell)
            }
            const eventFlexBasis = calculateEventFlexBasis(event.startTime,event.endTime);
            dailyCumulativeFlexBasis += eventFlexBasis;
            cell = document.createElement('div')
            cell.classList.add('event')
            cell.style.flexBasis = `${eventFlexBasis}%`;
            cell.innerHTML = `
                <span class = "event-time">${event.startTime}</span> - 
                <span class = "event-time">${event.endTime}</span> <br> 
                <span class = "event-title">${event.title}</span>`;
            column.appendChild(cell)
            prevEventEndTime = event.endTime

        }
        timetable.appendChild(column);

        const finalEventOfDay = dailyEvents[dailyEvents.length-1];
        if(finalEventOfDay.endTime !== "00:00"){
            paddingEvent = {
                "day": day,
                "startTime": finalEventOfDay.endTime,
                "endTime": "00:00",
                "title": "Playlist"
            };
            const paddingEventFlexBasis = calculateEventFlexBasis(paddingEvent.startTime,paddingEvent.endTime);
            cell = document.createElement('div');
            cell.classList.add('event');
            cell.style.flexBasis = `${100-dailyCumulativeFlexBasis}%`;
            cell.innerHTML = `
                <span class = "event-time">${paddingEvent.startTime}</span> - 
                <span class = "event-time">${paddingEvent.endTime}</span> <br> 
                <span class = "event-title">${paddingEvent.description}</span>`;
            column.appendChild(cell)
        }
        
    }
}

function get_schedule_body(){
    const FORM_GET_BODY = new FormData();
    FORM_GET_BODY.set('action','rw_schedule_get_body');

    return fetch(RW_URLS['wp_ajax'],
        {
            method:"POST",
            body:FORM_GET_BODY
        }
    )
    .then(function(res){
        console.log(res)
        if(res.ok)
            return res.text();
        else
            throw new Error("Failed to fetch contact-body from wp-admin-ajax API endpoint");
    })
    .catch((err)=>{
        console.log(err);
    })
    .then((res_html)=>{
        document.getElementById('main-body').innerHTML = res_html
    })
    .then(()=>{
        fetchTimetable().then((events)=>{
            renderTimetable(events)
        })
    })
}
