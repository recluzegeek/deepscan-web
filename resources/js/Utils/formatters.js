export const formatDate = (dateString) => {
    const localTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        timeZone: localTimeZone,
    };
    return new Intl.DateTimeFormat(undefined, options).format(
        new Date(dateString)
    );
};

export const formatPercentage = (value) => {
    return value ? `${(value * 100).toFixed(3)}%` : "N/A";
};
