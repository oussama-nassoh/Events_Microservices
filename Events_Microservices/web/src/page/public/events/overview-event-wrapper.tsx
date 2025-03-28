import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import useEventStore from "../../../service/store/event-store";
import OverviewEvent from "./overview-event";
import Spinner from '../../../components/sniper/sniper';

// Define the structure of the event object with required properties

export default function OverviewEventWrapper() {
    const { id } = useParams<{ id: string }>();  // id will be of type string
    const { fetchEvent } = useEventStore();
    const [event, setEvent] = useState<any | undefined>(undefined);

    useEffect(() => {
        async function loadEvent() {
            const eventId = id ? parseInt(id, 10) : 0;  // Ensure the id is parsed to a number
         //   let foundEvent = events.find((e: Event) => e.id === eventId);

          
                const response = await fetchEvent(eventId);
                console.log(response); // This shows the {status, data, message} structure
                
                // Extract the actual event data from the response
                if (response && response.status === 'success' && response.data) {
                    setEvent(response.data);
                }
            
            
            
        }

        loadEvent();
    }, [fetchEvent]);

    console.log(event);
    if (!event) return <div className="mt-25">  <Spinner/> </div>;

    return <OverviewEvent event={event} />;
}
