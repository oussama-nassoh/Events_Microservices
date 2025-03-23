import { useState } from 'react';
import { CloudArrowUpIcon, LockClosedIcon, ServerIcon } from '@heroicons/react/20/solid';
import useTicketStore from "../../../service/store/ticket-store.tsx";
import { CreateTicketRequest } from "../../../service/model/ticket.tsx";
import Spinner from "../../../components/sniper/sniper.tsx";

interface Event {
    id: number;
    title: string;
    description: string;
    date: string;
    location: string;
    available_tickets: number;
    speakers: string | null;
    sponsors: string | null;
    price: string;
    image: string;
    [key: string]: any; // Allow other properties to be dynamically added if necessary
}

interface OverviewEventProps {
    event: Event; // Define the type of the 'event' prop here
}

export default function OverviewEvent({ event }: OverviewEventProps) {
    const { createTicket } = useTicketStore();
    const [quantity, setQuantity] = useState(1);  // Default ticket quantity
    const [cardNumber, setCardNumber] = useState('');
    const [expiry, setExpiry] = useState('');
    const [cvv, setCvv] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);
    const userData = localStorage.getItem("user_data");
    const userLocal = userData ? JSON.parse(userData) : "";
    const id = typeof userLocal === "object" && userLocal !== null ? userLocal?.id : undefined;

    const handleQuantityChange = (e: any) => {
        setQuantity(parseInt(e.target.value));
    };

    const handlePaymentSubmit = async (e: any) => {
        e.preventDefault();
        setIsSubmitting(true);

        const paymentData: CreateTicketRequest = {
            event_id: event.id,
            quantity,
            payment: {
                card_number: cardNumber,
                expiry,
                cvv,
            },
        };

        try {
            await createTicket(id, paymentData);
        } catch (error) {
            console.error("Payment failed:", error);
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div className="relative isolate overflow-hidden bg-white py-24 sm:py-32">
            <div className="mx-auto max-w-7xl px-6 lg:px-8">
                <div className="mx-auto max-w-2xl lg:mx-0">
                    <p className="text-base font-semibold text-indigo-600">Upcoming Event</p>
                    <h1 className="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                        {event.title}
                    </h1>
                    
                    {/* Event image */}
                    {event.image && (
                        <div className="mt-6 overflow-hidden rounded-lg">
                            <img 
                                src={event.image} 
                                alt={event.title} 
                                className="w-full h-auto object-cover"
                            />
                        </div>
                    )}
                    
                    <p className="mt-6 text-xl text-gray-700">{event.description}</p>
                    <p className="mt-4 text-lg text-gray-600">
                        📅 {new Date(event.date).toLocaleDateString()} - 📍 {event.location}
                    </p>
                    <p className="mt-2 text-lg text-gray-600">🎟 {event.available_tickets} tickets available</p>
                    <p className="mt-2 text-lg text-gray-600">💰 ${parseFloat(event.price).toFixed(2)} per ticket</p>
                </div>

                {/* Speakers Section */}
                <div className="mt-16">
                    <h2 className="text-2xl font-bold text-gray-900">🎤 Speakers</h2>
                    <div className="mt-6 space-y-6">
                        {event.speakers ? (
                            <p className="text-lg font-semibold text-gray-900">{event.speakers}</p>
                        ) : (
                            <p className="text-lg text-gray-500">Speakers to be announced</p>
                        )}
                    </div>
                </div>

                {/* Sponsors Section */}
                <div className="mt-16">
                    <h2 className="text-2xl font-bold text-gray-900">🤝 Sponsors</h2>
                    <div className="mt-6 flex flex-wrap gap-6">
                        {event.sponsors ? (
                            <p className="mt-2 text-lg font-semibold text-gray-900">{event.sponsors}</p>
                        ) : (
                            <p className="mt-2 text-lg text-gray-500">Sponsors to be announced</p>
                        )}
                    </div>
                </div>

                {/* Features Section */}
                <div className="mt-16">
                    <h2 className="text-2xl font-bold tracking-tight text-gray-900">Why Attend?</h2>
                    <ul className="mt-6 space-y-6 text-gray-700">
                        <li className="flex gap-3">
                            <CloudArrowUpIcon className="size-6 text-indigo-600" />
                            <span>Get exclusive insights from industry leaders.</span>
                        </li>
                        <li className="flex gap-3">
                            <LockClosedIcon className="size-6 text-indigo-600" />
                            <span>Network with professionals and experts.</span>
                        </li>
                        <li className="flex gap-3">
                            <ServerIcon className="size-6 text-indigo-600" />
                            <span>Access valuable resources and opportunities.</span>
                        </li>
                    </ul>
                </div>

                {/* Payment Section */}
                <div className="mt-16">
                    <h2 className="text-2xl font-bold tracking-tight text-gray-900">🎟 Purchase Tickets</h2>
                    <div className="mt-4 p-6 bg-gray-50 rounded-lg border border-gray-200">
                        <form onSubmit={handlePaymentSubmit} className="space-y-6">
                            <div>
                                <label htmlFor="quantity" className="block text-sm font-medium text-gray-700">
                                    Ticket Quantity
                                </label>
                                <input
                                    type="number"
                                    id="quantity"
                                    value={quantity}
                                    onChange={handleQuantityChange}
                                    min="1"
                                    max={event.available_tickets}
                                    className="mt-1 block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                                <p className="mt-1 text-sm text-gray-500">
                                    Total: ${(parseFloat(event.price) * quantity).toFixed(2)}
                                </p>
                            </div>

                            <div>
                                <label htmlFor="cardNumber" className="block text-sm font-medium text-gray-700">
                                    Card Number
                                </label>
                                <input
                                    type="text"
                                    id="cardNumber"
                                    value={cardNumber}
                                    onChange={(e) => setCardNumber(e.target.value)}
                                    required
                                    placeholder="1234 5678 9012 3456"
                                    className="mt-1 block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                            </div>

                            <div className="flex gap-4">
                                <div className="w-1/2">
                                    <label htmlFor="expiry" className="block text-sm font-medium text-gray-700">
                                        Expiry Date
                                    </label>
                                    <input
                                        type="text"
                                        id="expiry"
                                        value={expiry}
                                        onChange={(e) => setExpiry(e.target.value)}
                                        required
                                        placeholder="MM/YY"
                                        className="mt-1 block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                </div>
                                <div className="w-1/2">
                                    <label htmlFor="cvv" className="block text-sm font-medium text-gray-700">
                                        CVV
                                    </label>
                                    <input
                                        type="text"
                                        id="cvv"
                                        value={cvv}
                                        onChange={(e) => setCvv(e.target.value)}
                                        required
                                        placeholder="123"
                                        className="mt-1 block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                </div>
                            </div>

                            <button
                                type="submit"
                                disabled={isSubmitting}
                                className="w-full mt-6 flex items-center justify-center bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:bg-gray-300 disabled:cursor-not-allowed"
                            >
                                {isSubmitting ? (
                                    <>
                                        <Spinner />
                                        <span className="ml-2">Processing...</span>
                                    </>
                                ) : (
                                    "Purchase Tickets"
                                )}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
}